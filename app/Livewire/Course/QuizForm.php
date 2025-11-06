<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class QuizForm extends Component
{
    use WithFileUploads;

    public Lesson $lesson;
    public ?Quiz $quiz = null;

    // Quiz settings
    public string $title = "";
    public int $passing_score = 70;
    public int $max_attempts = 1;

    // Questions data
    public array $questions = [];

    // File uploads (temporary)
    public array $questionImages = [];
    public array $optionImages = [];

    protected $rules = [
        "title" => "nullable|string|max:255",
        "passing_score" => "required|integer|min:0|max:100",
        "max_attempts" => "required|integer|min:1|max:10",
        "questions.*.question_text" =>
            "required_without:questionImages.*|string|max:500",
        "questions.*.image_url" => "nullable",
        "questions.*.options" => "required|array|min:2",
        "questions.*.options.*.option_text" =>
            "required_without:optionImages.*.*|string|max:255",
        "questions.*.options.*.image_url" => "nullable",
    ];

    public function mount(Lesson $lesson)
    {
        // Hanya pemilik course yang boleh akses
        if (auth()->id() !== $lesson->course->user_id) {
            abort(403, "Hanya pemilik kursus yang bisa mengelola kuis.");
        }

        $this->lesson = $lesson;
        $this->quiz = $lesson->quiz;

        if ($this->quiz) {
            $this->title = $this->quiz->title ?? "";
            $this->passing_score = $this->quiz->passing_score;
            $this->max_attempts = $this->quiz->max_attempts;

            $this->questions = $this->quiz->questions
                ->map(function ($q) {
                    return [
                        "question_text" => $q->question_text,
                        "image_url" => $q->image_url,
                        "options" => $q->options
                            ->map(function ($opt) {
                                return [
                                    "option_text" => $opt->option_text,
                                    "image_url" => $opt->image_url,
                                    "is_correct" => $opt->is_correct,
                                ];
                            })
                            ->toArray(),
                    ];
                })
                ->toArray();
        } else {
            // Mulai dengan 1 soal kosong
            $this->questions = [
                [
                    "question_text" => "",
                    "image_url" => null,
                    "options" => [
                        [
                            "option_text" => "",
                            "image_url" => null,
                            "is_correct" => false,
                        ],
                        [
                            "option_text" => "",
                            "image_url" => null,
                            "is_correct" => false,
                        ],
                    ],
                ],
            ];
        }
    }

    public function addQuestion()
    {
        $this->questions[] = [
            "question_text" => "",
            "image_url" => null,
            "options" => [
                [
                    "option_text" => "",
                    "image_url" => null,
                    "is_correct" => false,
                ],
                [
                    "option_text" => "",
                    "image_url" => null,
                    "is_correct" => false,
                ],
            ],
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption($questionIndex)
    {
        if (count($this->questions[$questionIndex]["options"]) < 4) {
            $this->questions[$questionIndex]["options"][] = [
                "option_text" => "",
                "image_url" => null,
                "is_correct" => false,
            ];
        }
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        if (count($this->questions[$questionIndex]["options"]) > 2) {
            unset($this->questions[$questionIndex]["options"][$optionIndex]);
            $this->questions[$questionIndex]["options"] = array_values(
                $this->questions[$questionIndex]["options"],
            );
        }
    }

    public function setCorrectAnswer($questionIndex, $optionIndex)
    {
        foreach (
            $this->questions[$questionIndex]["options"]
            as $key => $option
        ) {
            $this->questions[$questionIndex]["options"][$key][
                "is_correct"
            ] = false;
        }
        $this->questions[$questionIndex]["options"][$optionIndex][
            "is_correct"
        ] = true;
    }

    public function save()
    {
        $this->validate();

        // Validasi: setiap soal harus punya 1 jawaban benar
        foreach ($this->questions as $qIndex => $question) {
            $correctCount = collect($question["options"])->sum("is_correct");
            if ($correctCount !== 1) {
                $this->addError(
                    "questions.{$qIndex}.options",
                    "Setiap soal harus memiliki tepat 1 jawaban benar.",
                );
                return;
            }
        }

        // Simpan quiz
        $quizData = [
            "lesson_id" => $this->lesson->id,
            "title" => $this->title ?: null,
            "passing_score" => $this->passing_score,
            "max_attempts" => $this->max_attempts,
            "is_required" => false,
        ];

        if ($this->quiz) {
            $this->quiz->update($quizData);
            // Hapus soal lama
            foreach ($this->quiz->questions as $oldQuestion) {
                if (
                    $oldQuestion->image_url &&
                    Storage::disk("public")->exists(
                        parse_url($oldQuestion->image_url, PHP_URL_PATH),
                    )
                ) {
                    Storage::disk("public")->delete(
                        parse_url($oldQuestion->image_url, PHP_URL_PATH),
                    );
                }
                foreach ($oldQuestion->options as $opt) {
                    if (
                        $opt->image_url &&
                        Storage::disk("public")->exists(
                            parse_url($opt->image_url, PHP_URL_PATH),
                        )
                    ) {
                        Storage::disk("public")->delete(
                            parse_url($opt->image_url, PHP_URL_PATH),
                        );
                    }
                }
                $oldQuestion->delete();
            }
        } else {
            $this->quiz = Quiz::create($quizData);
        }

        // Simpan soal & opsi baru
        foreach ($this->questions as $qIndex => $questionData) {
            // Simpan gambar pertanyaan jika ada
            $questionImageUrl = null;
            if (!empty($this->questionImages[$qIndex])) {
                $path = $this->questionImages[$qIndex]->store(
                    "quiz/questions",
                    "public",
                );
                $questionImageUrl = asset("storage/" . $path);
            } else {
                $questionImageUrl = $questionData["image_url"] ?? null;
            }

            $question = $this->quiz->questions()->create([
                "question_text" => $questionData["question_text"],
                "image_url" => $questionImageUrl,
                "type" => "multiple_choice",
            ]);

            foreach ($questionData["options"] as $oIndex => $optionData) {
                $optionImageUrl = null;
                if (!empty($this->optionImages[$qIndex][$oIndex] ?? null)) {
                    $path = $this->optionImages[$qIndex][$oIndex]->store(
                        "quiz/options",
                        "public",
                    );
                    $optionImageUrl = asset("storage/" . $path);
                } else {
                    $optionImageUrl = $optionData["image_url"] ?? null;
                }

                $question->options()->create([
                    "option_text" => $optionData["option_text"],
                    "image_url" => $optionImageUrl,
                    "is_correct" => $optionData["is_correct"],
                ]);
            }
        }

        session()->flash("message", "Kuis berhasil disimpan!");
        return redirect()->route("lessons.index", $this->lesson->course);
    }

    public function render()
    {
        return view("livewire.course.quiz-form");
    }
}

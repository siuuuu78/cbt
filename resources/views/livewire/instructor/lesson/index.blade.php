<x-app-layout title="Kelola Lesson">
    <div class="py-12">
        <livewire:lesson.lesson-index :course-id="request()->route('course')" />
    </div>
</x-app-layout>

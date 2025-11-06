<?php

// Di method store()
use Spatie\Permission\Models\Role;

// ... setelah user dibuat
$user = User::create([...]);

// Assign role student
$user->assignRole('student'); // pastikan role 'student' ada

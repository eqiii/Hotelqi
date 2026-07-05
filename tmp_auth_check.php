<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

try {
    $user = User::factory()->create([
        'email' => 'authcheck@example.com',
        'password' => Hash::make('password'),
    ]);
    echo "created user id={$user->id}\n";
    var_dump($user->password);
    $result = Auth::attempt(['email' => $user->email, 'password' => 'password']);
    echo "attempt=" . ($result ? 'true' : 'false') . PHP_EOL;
    echo "check=" . (Auth::check() ? 'true' : 'false') . PHP_EOL;
    echo "user=" . (Auth::user()?->id ?? 'none') . PHP_EOL;
} catch (Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
    header('Location: music-player.php'); // Redirect to the music player if already logged in
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Load users from the JSON file
    $users = json_decode(file_get_contents('users.json'), true);

    // Check if the user exists
    foreach ($users as $user) {
        if ($user['username'] == $username) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                
                // Redirect to music player page
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid password!";
                break;
            }
        }
    }

    if (empty($error)) {
        $error = "Username not found!";
    }
}
?>
<header class="bg-neutral-900 text-white p-4 fixed w-full z-10 top-0">
    <div class="container mx-auto flex justify-between items-center">
        <a class="flex justify-center" href="../sites/index.php"> 
            <img src="../imgs/logoweb.png" class="w-16 h-16 rounded-lg mb-6">
            <h1 class="text-3xl font-bold mt-1 ml-2">Supernatural</h1>
        </a>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-pink-300">Home</a></li>
                <li><a href="about.html" class="hover:text-pink-300">About</a></li>
                <li><a href="contact.html" class="hover:text-pink-300">Contact</a></li>
                <?php
                // Check if the user is logged in and has the 'admin' role
                if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true && $_SESSION['role'] === 'admin') {
                    // Show this <li> only for admin users
                    echo '<li><a href="admin-panel.php" class="hover:text-pink-300">Admin Dashboard</a></li>';
                }
                ?>
                <li><a href="logout.php" class="hover:text-pink-300">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
<!-- Login Form -->
<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php
// Display error message if there is one
if ($error) {
    echo "<p style='color:red;'>$error</p>";
}
?>

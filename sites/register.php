<?php
session_start();

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];  // Get email from form

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Load existing users from the JSON file
    $users = json_decode(file_get_contents('users.json'), true);

    // Check if the username already exists
    foreach ($users as $user) {
        if ($user['username'] == $username) {
            $error = "Username already exists!";
            break;
        }
    }

    // If username doesn't exist and email is valid, register the user
    if (!isset($error)) {
        $newUser = [
            'username' => $username,
            'password' => hashPassword($password),
            'email' => $email,  // Store the email
            'role' => 'user'     // Add role as 'user'
        ];

        // Add new user to the array
        $users[] = $newUser;

        // Save the updated users array to the JSON file
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Set session variables for login state
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';  // Store role
        $_SESSION['email'] = $email; // Store email

        header('Location: ../sites/index.php'); // Redirect to music player page
        exit;
    }
}
?>

<!-- Registration form -->

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


<form method="POST" action="register.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<?php
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<?php
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];  // Get email from form
    $role = $_POST['role'];  // Capture role from form

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
            'role' => $role  // Store the selected role
        ];

        // Add new user to the array
        $users[] = $newUser;

        // Save the updated users array to the JSON file
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Redirect back to the admin dashboard or confirmation page
        header('Location: admin-panel.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User / Supernatural</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-neutral-800 h-screen flex flex-col">
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

<div class="flex justify-center items-center flex-1 pt-24">
    <form method="POST" action="add-user.php" class="flex flex-col items-center max-w-lg bg-neutral-700 p-12 m-4 mb-8 rounded-lg">
        <h1 class="text-white text-3xl p-2 mb-10">Create User</h1>
        <input class="rounded-lg p-3 m-3 w-full" type="text" name="username" placeholder="Username" required>
        <input class="rounded-lg p-3 m-3 w-full" type="email" name="email" placeholder="Email" required>
        <input class="rounded-lg p-3 m-3 w-full" type="password" name="password" placeholder="Password" required>

        <div class="flex p-3 bg-neutral-900 rounded-lg m-3 w-full">
            <label class="text-white m-3" for="role">Role:</label>
            <select class="rounded-lg w-full p-2" name="role" id="role" required> 
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="p-3 bg-pink-500 rounded-lg m-3 hover:bg-pink-400 transition-colors duration-300 ease-in-out active:scale-95">
            Add User
        </button>
    </form>
</div>

</body>
</html>

<?php
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

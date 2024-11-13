<?php
session_start();

// Only allow admins to delete users
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if the request is a POST request and the username is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $usernameToDelete = $_POST['username'];

    // Load the current users from users.json
    $users = json_decode(file_get_contents('users.json'), true);

    if ($users === null) {
        echo "Error: Unable to read users.json or invalid JSON format.";
        exit;
    }

    // Print users before deletion for debugging
    /*
    echo "Users before deletion:<pre>";
    print_r($users);
    echo "</pre>";
*/
    // Filter out the user with the matching username
    $updatedUsers = array_filter($users, function($user) use ($usernameToDelete) {
        return $user['username'] !== $usernameToDelete;
    });

    // Print updated users array for debugging
    echo "Users after deletion attempt:<pre>";
    print_r($updatedUsers);
    echo "</pre>";

    // Save the updated users array back to users.json
    if (file_put_contents('users.json', json_encode(array_values($updatedUsers), JSON_PRETTY_PRINT))) {
        echo "User deleted successfully.";
        // Redirect back to the admin dashboard
        header('Location: admin-panel.php');
        exit;
    } else {
        echo "Error: Failed to write to users.json.";
    }
} else {
    echo "Error: Username not received.";
}
header('Location: admin-panel.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<header class="bg-neutral-900 text-white p-4 fixed w-full z-10 top-0">
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
</header>, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
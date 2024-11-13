<?php
session_start();

// Load users from JSON file
$users = json_decode(file_get_contents('users.json'), true);

// Get username from query string
$usernameToEdit = $_GET['username'] ?? null;

// Find the user to edit
$userToEdit = null;
foreach ($users as $user) {
    if ($user['username'] === $usernameToEdit) {
        $userToEdit = $user;
        break;
    }
}

// If user not found, redirect back
if (!$userToEdit) {
    header('Location: admin-panel.php');
    exit;
}

// Update user if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedUsername = $_POST['username'];
    $updatedEmail = $_POST['email'];
    $updatedRole = $_POST['role'];

    // Update user details in the users array
    foreach ($users as &$user) {
        if ($user['username'] === $usernameToEdit) {
            $user['username'] = $updatedUsername;
            $user['email'] = $updatedEmail;
            $user['role'] = $updatedRole;
            break;
        }
    }

    // Save updated data to JSON file
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

    // Redirect to admin panel
    header('Location: admin-panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../imgs/logo.png" type="image/x-icon">
</head>
<body class="bg-neutral-800">
<header class="bg-neutral-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a class="flex justify-center"> 
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
<form method="POST" action="edit-user.php?username=<?php echo htmlspecialchars($usernameToEdit); ?>" class="flex flex-col items-center max-w-lg bg-neutral-700 p-12 m-4 mb-8 rounded-lg">
    <h1 class="text-white text-3xl p-2 mb-10">Edit User</h1>
    <label>Username:</label>
    <input class="rounded-lg p-3 m-3 w-full" type="text" name="username" value="<?php echo htmlspecialchars($userToEdit['username']); ?>" required>

    <label>Email:</label>
    <input class="rounded-lg p-3 m-3 w-full" type="email" name="email" value="<?php echo htmlspecialchars($userToEdit['email']); ?>" required>

    <label>Role:</label>
    <select class="rounded-lg p-3 m-3 w-full" name="role" required>
        <option value="user" <?php echo $userToEdit['role'] == 'user' ? 'selected' : ''; ?>>User</option>
        <option value="admin" <?php echo $userToEdit['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select>

    <button type="submit">Update User</button>
</form>
</div>
</body>
</html>


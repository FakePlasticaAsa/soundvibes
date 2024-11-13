<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not authorized
    exit;
}

// Load the users from the JSON file
$users = json_decode(file_get_contents('users.json'), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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


<h2 class="text-3xl text-white p-6">Admin Dashboard - User Management</h2>

<div class=" p-4 flex flex-col items-center justify-center">
    <table class="w-full h-full border-2 border-black border-collapse bg-neutral-900">
        <thead class="border-2 border-black">
            <tr>
                <th class="bg-neutral-950 text-white border-2 border-black px-4 py-2">Username</th>
                <th class="bg-neutral-950 text-white border-2 border-black px-4 py-2">Email</th>
                <th class="bg-neutral-950 text-white border-2 border-black px-4 py-2">Role</th>
                <th class="bg-neutral-950 text-white border-2 border-black px-4 py-2">Settings</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="text-white border-2 border-black px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="text-white border-2 border-black px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="text-white border-2 border-black px-4 py-2"><?php echo htmlspecialchars($user['role']); ?></td>
                    <th class="text-white border-2 border-black px-4 py-2"><div class="flex justify-around">
                        
                    <form method="GET" action="edit-user.php">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                        <button class="bg-green-700 p-2 rounded-lg">Edit</button>
                    </form>

                        <form action="delete-user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                        <button type="submit" class="bg-red-600 p-2 rounded-lg">Delete</button>

                    </form></div></th>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../sites/add-user.php" class="text-white bg-blue-700 p-2 m-4 w-full rounded-lg text-center"><button>Add a new user</button></a>
</div>



</body>
</html>
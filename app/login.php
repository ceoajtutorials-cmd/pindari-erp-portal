<?php
session_start();
if(isset($_SESSION['user'])){
    header("Location: dashboard.php");
    exit();
}
$error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    // Aapka purana login logic - abhi simple check
    if($email == 'admin@pindari.com' && $pass == 'admin123'){
        $_SESSION['user'] = 'Admin User';
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Galat Email ya Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Pindari Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a2342] flex items-center justify-center min-h-screen">
<div class="bg-white p-8 rounded-2xl w-[380px] shadow-2xl">
<div class="text-center mb-6">
<div class="w-16 h-16 bg-[#0a2342] text-white mx-auto rounded-xl flex items-center justify-center font-black text-xl">PE</div>
<h1 class="font-black text-[#0a2342] text-xl mt-2">PINDARI ENTERPRISES</h1>
<p class="text-xs text-gray-500">ERP PORTAL LOGIN</p>
</div>
<?php if($error){ echo "<div class='bg-red-100 text-red-600 p-2 rounded text-sm mb-3'>$error</div>"; }?>
<form method="POST">
<input name="email" type="email" required placeholder="admin@pindari.com" class="w-full border p-3 rounded-lg mb-3">
<input name="password" type="password" required placeholder="admin123" class="w-full border p-3 rounded-lg mb-4">
<button class="w-full bg-[#c4121f] text-white p-3 rounded-lg font-bold">Login</button>
</form>
<p class="text-[11px] text-center mt-4 text-gray-400">Demo: admin@pindari.com / admin123</p>
</div>
</body>
</html>

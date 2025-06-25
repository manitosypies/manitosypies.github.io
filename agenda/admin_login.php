<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Administrador</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f0e6ff; display: flex; justify-content: center; align-items: center; height: 100vh; }
    form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
    input { display: block; margin-bottom: 15px; padding: 10px; width: 100%; }
    button { background: #7e57c2; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; }
  </style>
</head>
<body>
  <form action="admin_login_validar.php" method="POST">
    <h2>Administrador - Iniciar sesión</h2>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
  </form>
</body>
</html>

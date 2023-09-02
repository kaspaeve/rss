<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
include 'config.php';
function displayToast($message, $type = 'error') {
    $bgColor = $type == 'error' ? 'bg-danger' : 'bg-success';
    $strongText = $type == 'error' ? 'Error' : 'Success';

    echo "
    <div class='toast position-absolute top-0 end-0 m-3' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='15000'>
        <div class='toast-header $bgColor text-white'>
            <strong class='me-auto'>$strongText</strong>
            <small class='text-muted'>Just now</small>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='toast' aria-label='Close'></button>
        </div>
        <div class='toast-body'>
            $message
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(event) { 
            var toastEl = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastEl, {delay: 15000});
            toast.show();
        });
    </script>";
}

function storeRememberMeToken($username, $token, $conn) {
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET remember_me_token=:hashedToken WHERE username=:username");

    $stmt->bindParam(':hashedToken', $hashedToken);
    $stmt->bindParam(':username', $username);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getUserByToken($token, $conn) {
    $stmt = $conn->prepare("SELECT id, remember_me_token FROM users WHERE remember_me_token IS NOT NULL");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($user = $result->fetch_assoc()) {
        if (password_verify($token, $user['remember_me_token'])) {
            return $user['id'];
        }
    }
    return null;
}
function loginUser($username, $password, $conn) {
    $sql = "SELECT id, password FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':username', $username); 

    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hashedPassword = $row['password'];
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $row['id'];
            return ['type' => 'success', 'message' => 'Login successful!'];
        } else {
            return ['type' => 'error', 'message' => 'Invalid password.'];
        }
    } else {
        return ['type' => 'error', 'message' => 'User not found.'];
    }
}
function handleLoginRequest($conn) {
    global $base_url;
    $errorMsg = '';
    $successMsg = '';

    if (isset($_SESSION['user_id'])) {
        header("Location: " . $base_url . "dashboard.php");
        exit();
    }

    if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
        $user_id = getUserByToken($_COOKIE['remember_me'], $conn);
        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            header("Location: " . $base_url . "dashboard.php");
            exit();
        } else {
            
            $successMsg = "Logged in via Remember Me token.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rememberMe = isset($_POST['remember_me']);

        $result = loginUser($username, $password, $conn);

        if (isset($result['type']) && $result['type'] === 'success') {
            if ($rememberMe) {
                $token = bin2hex(random_bytes(24));
                setcookie('remember_me', $token, time() + (86400 * 30), "/");

                if (!storeRememberMeToken($username, $token, $conn)) {
                    $errorMsg = "Failed to store Remember Me token.";
                }
            }
            header("Location: " . $base_url . "dashboard.php");
            exit();
        } elseif (isset($result['type']) && $result['type'] === 'error') {
            $errorMsg = $result['message'];
        }
    }

    return ['errorMsg' => $errorMsg, 'successMsg' => $successMsg];
}
function logoutUser($conn) {
    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("UPDATE users SET remember_me_token=NULL WHERE id=:user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        if (!$stmt->execute()) {
            // Log this error, or handle it in some other way
            error_log("Failed to nullify remember_me_token for user ID: " . $_SESSION['user_id']);
        }
        unset($_SESSION['user_id']);
    }
    // Remove remember_me cookie if it exists
    if (isset($_COOKIE['remember_me'])) {
        setcookie('remember_me', '', time() - 3600, '/');
    }
}
function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
function isLoggedIn() {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']);  
}
function getUsername($conn, $userId) {
    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['username'];
        } else {
            return null;
        }

    } catch (PDOException $e) {
        error_log("Error fetching username: " . $e->getMessage());
        return null;
    }
}

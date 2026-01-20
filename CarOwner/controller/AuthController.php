<?php
session_start();
require_once __DIR__ . '/../model/UserModel.php';

function handleRegistration()
{
    $fullName = $email = $phone = $password = $confirmPassword = "";
    $fullNameErr = $emailErr = $phoneErr = $passwordErr = $confirmPasswordErr = "";
    $successMsg = "";
    $errorMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["fullName"])) {
            $fullNameErr = "Full name is required!";
        } else {
            $fullName = trim($_POST["fullName"]);
            if (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {
                $fullNameErr = "Name can contain letters and spaces only!";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is required!";
        } else {
            $email = trim($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format!";
            }
        }

        if (empty($_POST["phone"])) {
            $phoneErr = "Phone number is required!";
        } else {
            $phone = trim($_POST["phone"]);

            if (!preg_match("/^01[0-9]{9}$/", $phone)) {
                $phoneErr = "Phone must be 11 digits and start with 01!";
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required!";
        } else {
            $password = $_POST["password"];
            if (strlen($password) < 6) {
                $passwordErr = "Password must be at least 6 characters!";
            }
        }

        if (empty($_POST["confirmPassword"])) {
            $confirmPasswordErr = "Please confirm your password!";
        } else {
            $confirmPassword = $_POST["confirmPassword"];
            if ($password !== $confirmPassword) {
                $confirmPasswordErr = "Passwords do not match!";
            }
        }


        if (empty($emailErr) && getUserByEmail($email)) {
            $emailErr = "Email already registered!";
        }


        if (empty($fullNameErr) && empty($emailErr) && empty($phoneErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
            $data = [
                'fullName' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password
            ];

            if (registerUser($data)) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $errorMsg = "Registration failed. Please try again.";
            }
        }
    }

    return compact('fullName', 'email', 'phone', 'fullNameErr', 'emailErr', 'phoneErr', 'passwordErr', 'confirmPasswordErr', 'successMsg', 'errorMsg');
}

function handleLogin()
{
    $email = $password = "";
    $emailErr = $passwordErr = "";
    $loginError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["email"])) {
            $emailErr = "Email is required!";
        } else {
            $email = trim($_POST["email"]);
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required!";
        } else {
            $password = $_POST["password"];
        }

        if (empty($emailErr) && empty($passwordErr)) {
            $user = loginUser($email, $password);
            if ($user) {

                if ($user['role'] === 'CarOwner') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_phone'] = $user['phone'];
                    $_SESSION['profile_picture'] = $user['profile_picture'];
                    header("Location: car_owner/dashboard.php");
                    exit();
                } else {
                    $loginError = "Access denied. Not a Car Owner account.";
                }
            } else {
                $loginError = "Invalid email or password.";
            }
        }
    }

    return compact('email', 'emailErr', 'passwordErr', 'loginError');
}

function logout()
{
    session_start();
    session_destroy();
    header("Location: /WIP/AutoPulse/Guest/public/index.php?page=login");
    exit();
}

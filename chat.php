<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shop';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all available product names on page load
$productList = "";
$query = "SELECT name FROM products ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $productList .= "<div class='bot'>📌 <strong>" . $product['name'] . "</strong></div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = isset($_POST['message']) ? trim($_POST['message']) : '';
    $response = "I couldn't find any matching products.";

    if (!empty($userInput)) {
        $query = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 5";
        $stmt = $conn->prepare($query);
        $searchTerm = "%$userInput%";
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = "Here are some products I found:<br>";
            while ($product = $result->fetch_assoc()) {
                $response .= "<br><strong>📌 " . $product['name'] . "</strong><br>";
                $response .= "💬 " . $product['description'] . "<br>";
                $response .= "💰 Price: PHP " . number_format($product['price'], 2) . "<br>";
                $response .= "📦 Stock: " . $product['stock'] . "<br>";
                $response .= "🏷️ Category: " . $product['category'] . "<br>";
                $response .= "---------------------------<br>";
            }
        } else {
            // Suggest related products if no exact match is found
            $response = "I couldn't find any matching products for '{$userInput}'. You might want to try searching for a broader term or check the spelling.";
        }
    }

    echo json_encode(['response' => $response]);
    $stmt->close();
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .chat-container {
            width: 100%;
            max-width: 800px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }
        .user, .bot {
            padding: 10px;
            margin: 5px;
            border-radius: 10px;
            max-width: 80%;
        }
        .user {
            background: #007bff;
            color: white;
            align-self: flex-end;
        }
        .bot {
            background: #e0e0e0;
            align-self: flex-start;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ccc;
        }
        input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 4px;
            background: #f4f4f4;
        }
        button {
            padding: 10px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
            margin-left: 5px;
            border-radius: 4px;
        }
        button:hover {
            background: #0056b3;
        }
        .product-list {
            position: absolute;
            top: 50px;
            left: 10px;
            background: #fff;
            padding: 10px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            max-width: 250px;
            z-index: 10;
        }
        .product-item {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">Product Chatbot</div>
        <?php echo $productList; ?>
        <div class="chat-box" id="chatBox"></div>
        <div class="chat-input">
            <input type="text" id="userMessage" placeholder="Ask about a product...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            let inputField = document.getElementById("userMessage");
            let userMessage = inputField.value.trim();
            if (userMessage === "") return;

            let chatBox = document.getElementById("chatBox");
            chatBox.innerHTML += `<div class='user'>${userMessage}</div>`;

            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "message=" + encodeURIComponent(userMessage)
            })
            .then(response => response.json())
            .then(data => {
                chatBox.innerHTML += `<div class='bot'>${data.response}</div>`;
                chatBox.scrollTop = chatBox.scrollHeight;
                inputField.value = "";
            });
        }
    </script>
</body>
</html>

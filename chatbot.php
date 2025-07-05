<?php
session_start();
include('db.php');

if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

$session_id = $_SESSION['session_id'];

// Handle AJAX request for sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        // Save user message to database
        $stmt = $conn->prepare("INSERT INTO chats (session_id, message, sender) VALUES (?, ?, 'user')");
        $stmt->bind_param("ss", $session_id, $message);
        $stmt->execute();

        // Call Deepseek API to get bot response (which will handle filtering and dish extraction)
        $bot_response = getDeepseekResponse($message);

        // Save bot response to database
        $stmt = $conn->prepare("INSERT INTO chats (session_id, message, sender) VALUES (?, ?, 'bot')");
        $stmt->bind_param("ss", $session_id, $bot_response);
        $stmt->execute();

        // Return bot response as JSON
        header('Content-Type: application/json');
        echo json_encode(['response' => $bot_response]);
        exit;
    }
}

function getDeepseekResponse($userMessage) {
    $apiKey = 'sk-31ae820378e34ce4930ec5d3bf374326'; // User provided Deepseek API key
    $apiUrl = 'https://api.deepseek.ai/chat'; // Hypothetical API endpoint

    $postData = json_encode([
        'message' => $userMessage,
        'session_id' => session_id()
    ]);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return "Sorry, I couldn't process your request right now.";
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['reply'])) {
        return $responseData['reply'];
    } else {
        return "Sorry, I couldn't understand that.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chatbot - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
        :root {
      --primary-color: #6b3f2a;
      --accent-color: #ffbd59;
      --text-color: #333;
      --hover-green: #43632f;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      padding: 20px;
      background-color: #f5f5f5;
    }

    #chat {
      margin-top: 70px;
      padding: 10px 16px;
    }
     .navbar {
      background-color: white !important;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      padding: 0.5rem 1.6rem;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }

    .navbar-brand span:first-child {
      color: black;
    }

    .navbar-brand span:last-child {
      color: var(--accent-color);
    }

    .navbar-nav .nav-link {
      color: black !important;
      font-weight: 700;
      margin-right: 20px;
      transition: background-color 0.3s;
    }

    .navbar-nav .nav-link:hover {
      background-color: var(--accent-color);
      color: white !important;
      border-radius: 8px;
    }
    .chat-container {
      width: auto;      
      margin: 0 auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      height: 80vh;
    }
    .chat-messages {
      flex-grow: 1;
      padding: 15px;
      overflow-y: auto;
      border-bottom: 1px solid #ddd;
    }
    .chat-input {
      display: flex;
      padding: 10px;
    }
    .chat-input input[type="text"] {
      flex-grow: 1;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }
    .chat-input button {
      margin-left: 10px;
      padding: 10px 20px;
      background-color: #6b3f2a;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .message {
      margin-bottom: 15px;
      max-width: 80%;
      padding: 10px 15px;
      border-radius: 15px;
      clear: both;
    }
    .message.user {
      background-color: #6b3f2a;
      color: white;
      float: right;
      border-bottom-right-radius: 0;
    }
    .message.bot {
      background-color: #ffbd59;
      color: black;
      float: left;
      border-bottom-left-radius: 0;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid ps-3">
    <a class="navbar-brand me-3" href="index.html">
      <span>Simply</span><span>Taste</span>
    </a>
    <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav me-3">
        <li class="nav-item"><a class="nav-link" href="index.html#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.html#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="index.html#menu">Menu</a></li>
      </ul>
    </div>
  </div>
</nav>

<section id = 'chat'>
  <div class="chat-container">
    <div class="chat-messages" id="chatMessages">
      <!-- Chat messages will appear here -->
    </div>
    <form id="chatForm" class="chat-input">
      <input type="text" id="messageInput" placeholder="Type your message..." autocomplete="off" required />
      <button type="submit">Send</button>
    </form>
  </div>
  </section>

  <script>
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');

    // Function to scroll chat to bottom
    function scrollToBottom() {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Load previous chat messages from server
    async function loadChat() {
      const response = await fetch('chat_history.php');
      if (response.ok) {
        const data = await response.json();
        chatMessages.innerHTML = '';
        data.forEach(msg => {
          const div = document.createElement('div');
          div.classList.add('message', msg.sender);
          div.textContent = msg.message;
          chatMessages.appendChild(div);
        });
        scrollToBottom();
      }
    }

    loadChat();

    chatForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const message = messageInput.value.trim();
      if (message === '') return;

      // Display user message immediately
      const userDiv = document.createElement('div');
      userDiv.classList.add('message', 'user');
      userDiv.textContent = message;
      chatMessages.appendChild(userDiv);
      scrollToBottom();

      // Show typing indicator
      const typingDiv = document.createElement('div');
      typingDiv.classList.add('message', 'bot');
      typingDiv.textContent = 'Typing...';
      chatMessages.appendChild(typingDiv);
      scrollToBottom();

      // Send message to server
      const response = await fetch('chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ message })
      });

      // Remove typing indicator
      chatMessages.removeChild(typingDiv);

      if (response.ok) {
        const data = await response.json();
        const botDiv = document.createElement('div');
        botDiv.classList.add('message', 'bot');
        botDiv.textContent = data.response;
        chatMessages.appendChild(botDiv);
        scrollToBottom();
      } else {
        const botDiv = document.createElement('div');
        botDiv.classList.add('message', 'bot');
        botDiv.textContent = "Sorry, something went wrong.";
        chatMessages.appendChild(botDiv);
        scrollToBottom();
      }

      messageInput.value = '';
      messageInput.focus();
    });
  </script>
</body>
</html>

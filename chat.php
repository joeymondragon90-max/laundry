<?php
session_start();
$shop_name = $_GET['shop'] ?? 'Unknown Shop';
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($shop_name); ?> - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .chat-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        .chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .chat-close:hover {
            transform: scale(1.2);
        }

        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f8fafc;
        }

        .message {
            margin-bottom: 15px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.customer {
            text-align: right;
        }

        .message-content {
            display: inline-block;
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 12px;
            word-wrap: break-word;
            font-size: 0.95rem;
        }

        .message.customer .message-content {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.shop .message-content {
            background: #e2e8f0;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 5px;
        }

        .chat-input-area {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        .chat-form {
            display: flex;
            gap: 10px;
        }

        .chat-form input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .chat-form input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .chat-form button {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .chat-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .chat-form button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .login-prompt {
            padding: 30px;
            text-align: center;
            background: #fef2f2;
            border-radius: 12px;
        }

        .login-prompt p {
            margin: 10px 0;
            color: #dc2626;
        }

        .login-prompt a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .sender-info {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .loading-indicator {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #2563eb;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .chat-container {
                margin: 10px;
                height: calc(100vh - 20px);
                display: flex;
                flex-direction: column;
            }

            .chat-messages {
                height: auto;
                flex: 1;
            }

            .message-content {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2><i class="fas fa-comments"></i> Chat with <?php echo htmlspecialchars($shop_name); ?></h2>
            <a href="javascript:history.back()" class="chat-close" title="Close"><i class="fas fa-times"></i></a>
        </div>

        <?php if (!$is_logged_in): ?>
            <div class="chat-input-area">
                <div class="login-prompt">
                    <p><i class="fas fa-info-circle"></i> You must be logged in to chat</p>
                    <p>
                        <a href="login.php?return=chat.php?shop=<?php echo urlencode($shop_name); ?>">
                            <i class="fas fa-sign-in-alt"></i> Log In
                        </a>
                        or
                        <a href="register.php">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="chat-messages" id="chatMessages">
                <div class="loading-indicator">
                    <div class="spinner"></div>
                    <p>Loading messages...</p>
                </div>
            </div>

            <div class="chat-input-area">
                <form class="chat-form" id="chatForm">
                    <input 
                        type="text" 
                        id="messageInput" 
                        placeholder="Type your message..." 
                        maxlength="500"
                        required
                    >
                    <button type="submit" id="sendBtn">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const shopName = '<?php echo htmlspecialchars($shop_name); ?>';
        const userName = '<?php echo htmlspecialchars($user_name); ?>';
        let lastMessageId = 0;
        let isPolling = true;

        // Load messages on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadMessages();
            // Poll for new messages every 3 seconds
            setInterval(loadMessages, 3000);

            // Handle form submission
            document.getElementById('chatForm')?.addEventListener('submit', sendMessage);
        });

        function loadMessages() {
            if (!isPolling) return;

            fetch(`get_messages.php?shop_name=${encodeURIComponent(shopName)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayMessages(data.messages);
                    }
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        function displayMessages(messages) {
            const chatDiv = document.getElementById('chatMessages');
            
            if (messages.length === 0) {
                chatDiv.innerHTML = '<div class="loading-indicator"><p>No messages yet. Start the conversation!</p></div>';
                return;
            }

            chatDiv.innerHTML = '';
            
            messages.forEach(msg => {
                const messageEl = document.createElement('div');
                messageEl.className = `message ${msg.sender_type}`;
                
                const time = new Date(msg.timestamp).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                messageEl.innerHTML = `
                    <div class="sender-info">${msg.sender_name}</div>
                    <div class="message-content">${msg.message}</div>
                    <div class="message-time">${time}</div>
                `;
                
                chatDiv.appendChild(messageEl);
            });

            // Auto scroll to bottom
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }

        function sendMessage(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            const sendBtn = document.getElementById('sendBtn');

            if (!message) return;

            sendBtn.disabled = true;
            isPolling = false;

            const formData = new FormData();
            formData.append('shop_name', shopName);
            formData.append('message', message);
            formData.append('sender_type', 'customer');

            fetch('chat_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    loadMessages();
                    isPolling = true;
                } else {
                    alert('Failed to send message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending message');
            })
            .finally(() => {
                sendBtn.disabled = false;
            });
        }

        // Pause polling when page is hidden, resume when visible
        document.addEventListener('visibilitychange', () => {
            isPolling = !document.hidden;
        });
    </script>
</body>
</html>

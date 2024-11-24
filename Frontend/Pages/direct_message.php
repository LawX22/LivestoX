<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Chat</title>
    <script type="module" src="../../js/vue/direct-message.js" async></script>
    <link rel="stylesheet" href="../../css/message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chat-window {
            width: 400px;
            max-width: 100%;
            height: 80vh;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background-color: #52b788;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }

        .chat-header .profile-info {
            display: flex;
            align-items: center;
        }

        .chat-header .profile-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .chat-header .calendar-icon {
            font-size: 20px;
        }

        .chat-convo {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .start-info {
            text-align: center;
            color: #6c757d;
            margin: 15px 0;
            font-size: 14px;
        }

        .chat-message {
            display: flex;
            margin: 10px;
            align-items: flex-end;
        }

        .chat-message.left {
            justify-content: flex-start;
        }

        .chat-message.right {
            justify-content: flex-end;
        }

        .chat-message p {
            max-width: 60%;
            padding: 10px;
            border-radius: 10px;
            margin: 0;
            font-size: 14px;
        }

        .chat-message.left p {
            background-color: #e9ecef;
            text-align: left;
            border-top-left-radius: 0;
        }

        .chat-message.right p {
            background-color: #52b788;
            color: white;
            text-align: right;
            border-top-right-radius: 0;
        }

        .chat-message img {
            max-width: 60%;
            margin-top: 5px;
            border-radius: 8px;
        }

        .chat-footer-container {
            position: relative;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 10px;
        }

        .chat-footer {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-footer input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
        }

        .chat-footer .send-button {
            background-color: #52b788;
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-footer .send-button:hover {
            background-color: #408a63;
        }

        .chat-footer label {
            cursor: pointer;
            font-size: 16px;
            color: #6c757d;
        }

        .chat-footer label:hover {
            color: #495057;
        }

        .chat-footer input[type="file"] {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Chat window -->
    <div id="direct-convo" class="chat-window">
        <div class="chat-header">
            <div class="profile-info">
                <img :src="'../../uploads/profile_pictures/' + profile_picture" alt="Profile Picture" class="main-profile-circle">
                <h3 id="username">{{ fullname }}</h3>
            </div>
            <div class="calendar-icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>

        <div class="chat-convo">
            <p class="start-info">A legendary conversation has been started</p>
            <div v-for="conversation in convo" :key="conversation.message_id" class="chat-message" :class="String(conversation.user_id) === String(current_user) ? 'right' : 'left'">
                <p>
                    {{ conversation.content }}
                </p>
                <img v-if="conversation.image_url && conversation.image_url !== ''" :src="'../../uploads/livestock_posts/' + conversation.image_url" alt="Image">
            </div>
        </div>

        <div class="chat-footer-container">
            <div class="chat-footer">
                <input type="text" placeholder="Type something..." v-model="message" @keyup.enter="sendMessage">
                <label for="upload-image">
                    <i class="fas fa-paperclip"></i>
                </label>
                <input type="file" id="upload-image" @change="handleFileUpload">
                <button class="send-button" @click="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</body>

</html>

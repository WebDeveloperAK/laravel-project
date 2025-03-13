<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
    {{-- <script src="js/index.js"></script> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
   
</head>

<body style="background-color: #202c33;">
    <script>
        function loadActiveUsers() {
            

    $.ajax({
        url: "{{ route('users.active') }}",
        type: "GET",
        success: function(response) {
            let output = "";
            if (response.allusers.length > 0) {
                console.log(response);
                response.allusers.forEach(user => {
                    let userName = user.name ? user.name : "Unknown"; 
                    let id = user.id;
                    let avatarUrl =
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=007bff&color=fff&rounded=true`;

                    output += `
                        <div class="chat-item" data-id="${id}">
                            <img src="${avatarUrl}" alt="${userName}" class="user-img">
                            <div class="chat-info">
                                <div class="chat-name">${userName}</div>
                                
                                <div class="chat-message">Hey, how are you?</div>
                            </div>
                        </div>
                    `;
                });
            } else {
                output = "<p><strong>No active users</strong></p>";
            }
            $("#allusersContainer").html(output);
        },
        error: function(xhr) {
            console.log("Error fetching users:", xhr.responseText);
        }
    });
}

// Handle click event on .chat-item
$(document).on("click", ".chat-item", function() {
    let userId = $(this).data("id");

    $.ajax({
    url: "{{ route('users.online') }}",
    type: "GET",
    data: { id: userId },
    success: function(response) {
        if (response.users.length > 0) {
            let name = response.users[0].name;
            let id = response.users[0].id;
            let avatarUrl =
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=007bff&color=fff&rounded=true`;

            $("#nameusers").html(name);
            $("#recivId").val(id);
            $("#avatarUrl").attr("src", avatarUrl);
            // console.log("Name:", name);
            console.log("ID:", id);
        } else {
            console.log("No user found");
        }
    },
    error: function(xhr) {
        console.log("Error fetching user data:", xhr.responseText);
    }
});

});

setInterval(loadActiveUsers, 5000);
loadActiveUsers();
      
    </script>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container d-flex justify-content-between">
        <div class="d-flex align-items-center">
        </div>
        <div>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>
    <div class="container-fluid">
        
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <input type="text" class="form-control mb-3" id="search" placeholder="Search menu...">
                <ul class="menu list-unstyled" id="menuList">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Messages
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="messagesDropdown">
                            <li><a class="dropdown-ite text-white" href="{{ route('message') }}">Inbox</a></li>
                            <li><a class="dropdown-ite text-white" href="{{ route('all.message') }}">All Archived</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </nav>
            
            <style>
                .dropdown-item:hover {
                    color: var(--bs-dropdown-link-hover-color);
                    background-color: #1a252f !important;
                }
            
                
            
                .dropdown-menu {
                    background: #1a252f;
                }
            </style>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="row">
                    <div class="col-lg-4">
                        {{-- <div class="chat-container scrollable-div" id="activeUsersContainer"></div> --}}
                        {{-- <hr> --}}
                        <div class="chat-container scrollable-div" id="allusersContainer"></div>
                    </div>
                    <div class="col-lg-8">
                        <div class="chat-header">
                            <!-- Left Section (Back Button & User Info) -->
                            <div class="user-info">
                                
                                <img id="avatarUrl" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=007bff&color=fff&rounded=true" alt="User">
                                <div>
                                    <div id="nameusers" style="font-weight: bold;">{{ auth()->user()->name }}</div>
                                    <small style="color: #b1b3b5;">Online</small>
                                </div>
                            </div>
                            <div class="header-icons">
                                <i class="bi bi-camera-video"></i>
                                <i class="bi bi-telephone"></i>
                                <i class="bi bi-three-dots-vertical"></i>
                            </div>
                        </div>
                        <div class="chat-box " id="chatBox">
                        </div>
                        <form id="chatForm">
                            <div class="input-group">
                                @csrf
                                <input type="hidden" id="recivId">
        
                                <input type="text" class="form-control" id="messageInput" name="message"
                                    autocomplete="off" placeholder="Type a message..."style="
                            border-top-left-radius: 6px;
                            border-bottom-left-radius: 6px;
                        ">
                                <label class="input-group-text" for="imageUpload">
                                    📎 <!-- You can replace this with an icon -->
                                </label>
                                <input type="file" hidden name="file" id="imageUpload" accept="image/*">
                                <input type="file" hidden id="audio">
                                <a href="#" class="input-group-text" id="record">
                                    <svg class="icon" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M11.999,14.942c2.001,0,3.531-1.53,3.531-3.531V4.35c0-2.001-1.53-3.531-3.531-3.531S8.469,2.35,8.469,4.35v7.061C8.469,13.412,9.999,14.942,11.999,14.942z M18.237,11.412c0,3.531-2.942,6.002-6.237,6.002s-6.237-2.471-6.237-6.002H3.761c0,4.001,3.178,7.297,7.061,7.885v3.884h2.354v-3.884c3.884-0.588,7.061-3.884,7.061-7.885L18.237,11.412z">
                                        </path>
                                    </svg>
        
                                </a>
                                <a href="#" class="input-group-text d-none" id="stop">⏹</a>
                                <div class="audio-controls mt-3" style="display: block;height: 0px;">
                                    {{-- <audio id="audio" class="w-100" controls></audio> --}}
        
                                </div>
                                <button class="btn btn-primary" type="submit" id="sendBtn">Send</button>
                            </div>
                        </form>
                    </div>
                
                
                </div>
                
            </main>
        </div>
    </div>
    
   
    <script>
        let mediaRecorder;
        let audioChunks = [];

        document.getElementById("record").addEventListener("click", async function(event) {
            event.preventDefault();
            let stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                let audioBlob = new Blob(audioChunks, {
                    type: 'audio/wav'
                });
                let audioFile = new File([audioBlob], `audio_${Date.now()}.wav`, {
                    type: 'audio/wav'
                });

                let audioInput = document.getElementById("audio");
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(audioFile);
                audioInput.files = dataTransfer.files; // Assign the recorded file to the hidden input

                let audioUrl = URL.createObjectURL(audioBlob);
                document.getElementById("audio").style.display = "block";
                document.querySelector(".audio-controls").style.display = "block";
            };

            mediaRecorder.start();
            document.getElementById("record").classList.add("d-none");
            document.getElementById("stop").classList.remove("d-none");
        });

        document.getElementById("stop").addEventListener("click", function(event) {
            event.preventDefault();
            mediaRecorder.stop();
            document.getElementById("record").classList.remove("d-none");
            document.getElementById("stop").classList.add("d-none");
        });
    </script>

    <script>
        let typingTimer;
        let isTyping = false;

        $("#messageInput").on("input", function() {
            if (!isTyping) {
                isTyping = true;
                $.ajax({
                    url: "{{ route('message.typing') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        typing: true
                    },
                });
            }

            clearTimeout(typingTimer);
            typingTimer = setTimeout(stopTyping, 2000);
        });

        function stopTyping() {
            isTyping = false;
            $.ajax({
                url: "{{ route('message.typing') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    typing: false
                },
            });
        }

        // Function to fetch typing users
        function fetchTypingUsers() {
            $.ajax({
                url: 'http://127.0.0.1:8000/message/fetch-typing',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.typingUsers && data.typingUsers.length > 0) {
                        // If the div doesn't exist, add it
                        if ($('#test').length === 0) {
                            $('#chatBox').append(`
                    <div class="message reciv" id="test" >
                        Typing <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                    </div>
                `);
                        }
                        // Update the text with the typing users
                        $('#test').text(data.typingUsers.join(', ') + ' is typing...');
                    } else {
                        $('#test').remove();
                    }
                }
            });

        }

        // Fetch typing status every 2 seconds
        // setInterval(fetchTypingUsers, 2000);

        setInterval(fetchTypingUsers, 2000);
    </script>
    <script>
        $(document).ready(function() {
            // Function to send a message
            $("#chatForm").submit(function(e) {
                e.preventDefault();

                let message = $("#messageInput").val();
                let recivId = $("#recivId").val();
                if (recivId == ''){
                    alert("Select a User For Messages");
                }
                
                console.log(message);
                let file = $("#imageUpload")[0].files[0];
                let audioInput = document.getElementById("audio");

                if (audioInput !== null && audioInput.files && audioInput.files.length > 0) {
                    var audioFile = audioInput.files[0];
                } else {
                    var audioFile = null; // No file selected or element missing
                }

                let formData = new FormData();
                    formData.append("recivId",recivId);
                if (message && !file && !audioFile) {
                    formData.append("message", message);
                } else if (file && !message && !audioFile) {
                    formData.append("file", file);
                } else if (audioFile && !message && !file) {
                    formData.append("audio", audioFile);
                } else {
                    alert("Please send only one type of input: Message, File, or Audio.");
                    return;
                }

                $.ajax({
                    url: "{{ route('message.store') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            loadMessages();
                            $("#messageInput").val("");
                            $("#imageUpload").val("");
                            $("#audio").val("");
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });



            function loadMessages() {
                $.ajax({
                    url: "{{ route('message.fetch') }}",
                    type: "GET",
                    success: function(data) {
                        $("#chatBox").html(data.messages);
                        $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            setInterval(loadMessages, 3000);

            loadMessages();
        });
    </script>
<script>
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.menu li');
        items.forEach(item => {
            item.style.display = item.innerText.toLowerCase().includes(filter) ? '' : 'none';
        });
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

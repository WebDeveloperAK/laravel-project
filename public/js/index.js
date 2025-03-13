function loadActiveUsers() {
    $.ajax({
url: "{{ route('users.active') }}",
type: "GET",
success: function(response) {
let output = "";

// Check if users exist and whether it's an array or an object
if (Array.isArray(response.users) && response.users.length > 0) {
    console.log(response);
    response.users.forEach(user => {
        let userName = user.name ? user.name : "Unknown"; 
        let id = user.id;
        let avatarUrl =
            `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=007bff&color=fff&rounded=true`;

        output += `
            <div class="chat-item" data-id="${id}">
                <img src="${avatarUrl}" alt="${userName}" class="user-img">
                <div class="chat-info">
                    <div class="chat-name">${userName}</div>
                    <div class="status-dot"></div>
                    <div class="chat-message">Hey, how are you?</div>
                </div>
            </div>
        `;
    });
} 
else if (typeof response.users === "object" && response.users !== null) {
    // If `response.users` is a single object instead of an array
    let user = response.users;
    let userName = user.name ? user.name : "Unknown"; 
    let id = user.id;
    let avatarUrl =
        `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=007bff&color=fff&rounded=true`;

    output += `
        <div class="chat-item" data-id="${id}">
            <img src="${avatarUrl}" alt="${userName}" class="user-img">
            <div class="chat-info">
                <div class="chat-name">${userName}</div>
                <div class="status-dot"></div>
                <div class="chat-message">${user.message}</div>
            </div>
        </div>
    `;
} 
else {
    // Handle empty response
    output = `<div class="chat-item" data-id="Unknown">
                <img src="blank-profile-picture-973460_640.webp" alt="Unknown" class="user-img">
                <div class="chat-info">
                    <div class="chat-name">Unknown</div>
                    <div class="status-dot"></div>
                    <div class="chat-message">Hey, how are you?</div>
                </div>
            </div>`;
}

$("#activeUsersContainer").html(output);
},
error: function(xhr) {
console.log("Error fetching users:", xhr.responseText);
}
});

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
let messages = [];
let displayName = localStorage.getItem('displayName') || "You";

const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const sendBtn = document.getElementById('sendBtn');
const refreshBtn = document.getElementById('refreshBtn');
const profileBtn = document.getElementById('profileBtn');
const profileModal = document.getElementById('profileModal');
const closeProfile = document.getElementById('closeProfile');
const displayNameInput = document.getElementById('displayName');
const saveProfile = document.getElementById('saveProfile');
async function fetchMessages() {
  try {
    const res = await fetch('getMessages.php');
    const data = await res.json();
    if (Array.isArray(data)) {
      messages = data;
      renderMessages();
    } else {
      console.error("Invalid message data:", data);
    }
  } catch (err) {
    console.error("Message fetch error:", err);
  }
}
function renderMessages() {
  chatMessages.innerHTML = '';

  messages.forEach(msg => {
    const div = document.createElement('div');
    div.className = 'msg user1';
    div.innerHTML = `<span class="user">${msg.username}:</span> <span class="text">${msg.message}</span>`;
    chatMessages.appendChild(div);
  });

  chatMessages.scrollTop = chatMessages.scrollHeight; 
}

async function sendMessage() {
  const text = chatInput.value.trim();
  if (text === "") return;

  try {
    const res = await fetch('sendMessage.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: text })
    });

    const result = await res.json();
    
    if (result.success) {
      chatInput.value = '';
      await fetchMessages();
    } else {
      console.error("Failed to send message:", result.error);
      alert("Failed to send message. Please try again.");
    }
  } catch (err) {
    console.error("Send message error:", err);
    alert("Error sending message. Please check your connection.");
  }
}
profileBtn.addEventListener('click', () => {
  displayNameInput.value = displayName;
  profileModal.style.display = 'block';
});

closeProfile.addEventListener('click', () => {
  profileModal.style.display = 'none';
});

saveProfile.addEventListener('click', () => {
  displayName = displayNameInput.value.trim() || "You";
  localStorage.setItem('displayName', displayName);
  profileModal.style.display = 'none';
});

window.onclick = function (event) {
  if (event.target == profileModal) {
    profileModal.style.display = "none";
  }
};
function fetchOnlineUsers() {
  fetch("getOnlineUsers.php")
    .then(res => res.json())
    .then(users => {
      const list = document.getElementById("userList");
      const count = document.getElementById("onlineCount");
      list.innerHTML = "";
      users.forEach(u => {
        let icon = "👤";
        if (u.gender === "Male") icon = "👨";
        else if (u.gender === "Female") icon = "👩";
        list.innerHTML += `
          <li>
            <span class="avatar">${icon}</span>
            <span class="user green">${u.username} (${u.age})</span>
          </li>`;
      });
      count.innerText = users.length;
    })
    .catch(err => console.error("Online fetch failed:", err));
}
function openPrivateChat(toUser) {
  document.getElementById("chatWithUser").innerText = toUser;
  document.getElementById("privateChat").style.display = "block";
  loadPrivateMessages(toUser);

  document.getElementById("privateSendBtn").onclick = () => {
    const input = document.getElementById("privateInput");
    const msg = input.value.trim();
    if (!msg) return;

    fetch("sendPrivate.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "receiver=" + encodeURIComponent(toUser) + "&message=" + encodeURIComponent(msg)
    }).then(() => {
      input.value = "";
      loadPrivateMessages(toUser);
    });
  };
}

function loadPrivateMessages(toUser) {
  fetch("getPrivateMessages.php?with=" + encodeURIComponent(toUser))
    .then(res => res.json())
    .then(messages => {
      const box = document.getElementById("privateMessages");
      box.innerHTML = "";
      messages.forEach(m => {
        box.innerHTML += `<div><strong>${m.sender}:</strong> ${m.message}</div>`;
      });
      box.scrollTop = box.scrollHeight;
    });
}

document.getElementById("closePrivateChat").onclick = () => {
  document.getElementById("privateChat").style.display = "none";
};

sendBtn.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', function (e) {
  if (e.key === 'Enter') sendMessage();
});
refreshBtn.addEventListener('click', fetchMessages);

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("user")) {
    const toUser = e.target.textContent.trim().split(" ")[0];
    openPrivateChat(toUser);
  }
});

fetchMessages();
fetchOnlineUsers();
setInterval(fetchMessages, 2000);
setInterval(fetchOnlineUsers, 5000);

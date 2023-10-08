<ul id="messages"></ul>
      <input id="message" placeholder="Enter message" autocomplete="off">
 
    <input type="submit" onclick="sendMessage();">


<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>

<!-- include firebase database -->
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
       apiKey: "AIzaSyAZEPJAzpAS5NLAgZTp-I4cPUgO0QiSs0A",
       authDomain: "fir-chats-e1011.firebaseapp.com",
       databaseURL: "https://fir-chats-e1011-default-rtdb.firebaseio.com",
       projectId: "fir-chats-e1011",
       storageBucket: "fir-chats-e1011.appspot.com",
       messagingSenderId: "297962266408",
       appId: "1:297962266408:web:e7463516988c9c49d57a1d",
       measurementId: "G-GRGM4J205J"
   };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>


   
     
<script>
    function sendMessage() {
        // get message
        var message = document.getElementById("message").value;

        // save in database
        firebase.database().ref("messages").push().set({
            "sender": "ssssssss",
            "message": message
        });
 
        // prevent form from submitting
        return false;
    }


    function deleteMessage(self) {
    // get message ID
    var messageId = self.getAttribute("data-id");
 
    // delete message
    firebase.database().ref("messages").child(messageId).remove();
}
 
// attach listener for delete message
firebase.database().ref("messages").on("child_removed", function (snapshot) {
    // remove message node
    document.getElementById("message-" + snapshot.key).innerHTML = "This message has been removed";
});
</script>
<script>
    // listen for incoming messages
    firebase.database().ref("messages").on("child_added", function (snapshot) {
        var html = "";
        // give each message a unique ID
        html += "<li id='message-" + snapshot.key + "'>";
        // show delete button if message is sent by me
        if (snapshot.val().sender == 'sssss') {
            html += "<button data-id='" + snapshot.key + "' onclick='deleteMessage(this);'>";
                html += "Delete";
            html += "</button>";
        }
        html += snapshot.val().sender + ": " + snapshot.val().message;
        html += "</li>";
 
        document.getElementById("messages").innerHTML += html;
    });
</script>
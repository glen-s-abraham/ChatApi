<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <div id="messages">
   
  </div>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('a0c5ccfe5a592f729da2', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('messageChannel');
    channel.bind('messageEvent', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
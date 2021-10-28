   //This script records the time in seconds when the page loads and then sends the 
   //total time spent via ajax post when user is detected to leave the page

   let startTime = Math.floor(Date.now() / 1000); //seconds since epoch
   let timeNow;
   let secondsOn;
   //https://developer.mozilla.org/en-US/docs/Web/API/Document/visibilitychange_eve
     document.addEventListener("visibilitychange", function logTime() {
  if (document.visibilityState === 'hidden') {
      timeNow = Math.floor(Date.now() / 1000);
      secondsOn = timeNow - startTime;//get the total seconds spent on the page
      console.log("startTime: "+startTime+ " timeNow: "+ timeNow +" secondsOn: " + secondsOn);
    $data = JSON.stringify({id: currentGameID, seconds: secondsOn, color: colorForTime});
        navigator.sendBeacon("/sloshogi/test.php", $data);
  }else{
    startTime = Math.floor(Date.now() / 1000);
    console.log("visible again startTime: "+startTime+" colorForTime: "+colorForTime);
  } 
});

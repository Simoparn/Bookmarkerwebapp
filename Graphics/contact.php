

<div id="pagecontent">
  <p>
  You can contact us at:
  </p>
  <p>
    <ul class="pagecontentlist">
      <li>https://nanparensoft.com</li>
      <li>email: nanparensoft@gmail.com</li>
      <li>With the form underneath</li>
    </ul>
  </p>  
  
  <p>
    <form method="post" action=".\Eventhandlers\send_feedback_email.php">
    Name<input type="text" name="name" id="name">
    <br>Email<input type="email" name="email" id="email" pattern="^[\w._%+-]+@[\w.-]+\.[a-z]{2,}$"required>  <br>   
    <select name="feedbacktopic" id="feedbacktopic">
      <option value="Quesiton about products">Question about products</option>
      <option value="Order">Order</option>
      <option value="Contact request">Contact request</option>
      <option value="Other">Other</option>
    </select>
    <br>Message<input type="text" name="feedbackmessage" id="feedbackmessage">
    I wish to order the company's newsletter: 
    <br><input type="radio" name="newsletter" id="newsletter-yes" value="yes" class="customradio">
    <label for="newsletter-yes">Yes</label>
    <input type="radio" name="newsletter" id="newsletter-no" value="no" class="customradio">
    <label for="newsletter-no">No</label><br>
    <input type="submit" name="form" id="form" value="Send feedback" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); 
    buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Feedback send button pressed. Please wait or correct the errors in the form';">
    <span id="buttonpressedtext"></span>
    </form>
  </p>
</div>
    
        
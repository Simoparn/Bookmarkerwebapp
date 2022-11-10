

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
    Name<input type="text" name="nimi" id="nimi">
    <br>Email<input type="email" name="sähköposti" id="sähköposti" pattern="^[\w._%+-]+@[\w.-]+\.[a-z]{2,}$"required>  <br>   
    <select name="palauteaihe" id="palauteaihe">
      <option value="Kysymys tuotteista">Kysymys tuotteista</option>
      <option value="Tilaus">Tilaus</option>
      <option value="Yhteydenottopyyntö">Yhteydenottopyyntö</option>
      <option value="other">Muu</option>
    </select>
    <br>Message<input type="text" name="palauteviesti" id="palauteviesti">
    I wish to order the company's newsletter: 
    <br><input type="radio" name="newsletter" id="newsletter-yes" value="yes" class="customradio">
    <label for="newsletter-yes">Kyllä</label>
    <input type="radio" name="newsletter" id="newsletter-no" value="no" class="customradio">
    <label for="newsletter-no">Ei</label><br>
    <input type="submit" name="lomake" id="lomake" value="Lähetä palaute" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); 
    buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Feedback send button pressed. Please wait or correct the errors in the form';">
    <span id="buttonpressedtext"></span>
    </form>
  </p>
</div>
    
        
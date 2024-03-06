const wrapper = document.querySelector('.wrapper');
const signinLink = document.querySelector('.signin-link');
const registerLink = document.querySelector('.register-link');
const btnpopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.addEventListener('click', ()=>{
   wrapper.classList.add('active');
});

signinLink.addEventListener('click',()=>{
    wrapper.classList.remove('active');
 });

 btnpopup.addEventListener('click', ()=>{
    wrapper.classList.add('active-popup');
 });

 iconClose.addEventListener('click', ()=>{
    wrapper.classList.remove('active-popup');
 });
 function validate(){
   var username = document.getElementById("uname").value;
   var email = document.getElementById("email").value;
   var password = document.getElementById("pass").value;
   var repassword = document.getElementById("repass").value;
   //cheking for empty field also known as basic validation
   //checking for emptiness of text box
   if(username.trim()==""||password.trim()==""||repassword.trim()==""||email.trim()==""||phone.trim()==""){
       alert("username,password, or email field is empty. please fill");
       return false;
   }
   else if(username.trim().length<3||username.trim().length>25){
       alert("username should be between 3 to 25 character");
       return false;
   }
   else if(password.trim().length<8){
       alert("password too short");
       return false;
   }
   else if(email.indexOf('@')<=0||email.charAt(email.length-4)!='.'){
       alert("please insert in correct format");
       return false;

   }
   else{
       alert("all are correct. form submitted");
       return true;
   }
}
function clearNotifications(){ 
    const errornotification=document.getElementsByClassName('errormessage');
    console.log(errornotification);
        if(errornotification[0] != undefined){
            errornotification[0].remove();
        }
    const successnotification=document.getElementsByClassName('successmessage'); 
        if(successnotification[0] != undefined){
            successnotification[0].remove();
        }
}
document.addEventListener('DOMContentLoaded', function(){
    setTimeout(clearNotifications,10000);
})
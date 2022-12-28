buttonSell = window.document.getElementById('sell');
buttonSend = window.document.getElementById('send');
sendForm = window.document.getElementById('send-form');
sellForm = window.document.getElementById('sell-form');
if (buttonSell != null && buttonSend != null && sendForm != null && sellForm != null) {
    buttonSend.addEventListener('click',($e)=>{
        sendForm.style.display = 'flex';
        sellForm.style.display = 'none';
    });
    buttonSell.addEventListener('click',($e)=>{
        sellForm.style.display = 'flex';
        sendForm.style.display = 'none';
    })
}
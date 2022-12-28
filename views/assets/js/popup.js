popup = window.document.getElementById('popup');
if (popup!=null){
    button = window.document.getElementById('popup-button');
    if (button!=null){
        button.addEventListener('click',($e)=>{
            popup.style.display='none';
        })
    }
}
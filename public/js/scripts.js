function ConfirmDelete(url){
    if( confirm('Are you sure you want to delete this order?') ){
        window.location.href = url;
    }
}
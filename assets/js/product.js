function addToCart(p_id) {
    $.ajax({
        type : 'POST',
        url  : '/do.php?cart',
        data : {product_id: p_id},
        success : function(response){
            if(response === "Auth Error"){
                swal({
                    title: "Error",
                    text: "Login to process",
                    icon: "error",
                });
            } else if (response === "success") {
                swal({
                    title: "Success",
                    text: "Added To cart",
                    icon: "success",
                }).then(value => {
                    location.reload();
                });
            } else if (response === "Already done") {
                alert(response);
            }
        }
    });
    return false;
}

function removeToCart(p_id) {
    $.ajax({
        type : 'POST',
        url  : '/do.php?cartRemove',
        data : {product_id: p_id},
        success : function(response){
            if(response === "Auth Error"){
                swal({
                    title: "Error",
                    text: "Login to process",
                    icon: "error",
                });
            } else if (response === "success") {
                swal({
                    title: "Success",
                    text: "Removed from cart",
                    icon: "success",
                }).then(value => {
                    location.reload();
                });
            } else if (response === "Already done") {
                alert(response);
            }
        }
    });
    return false;
}
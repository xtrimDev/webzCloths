function add_new_field() {
    let data_container = document.getElementById('data_container');
    data_container.innerHTML += `
    <div class="box">
        <input type="text" placeholder="Data Title" name="data_title[]" class="data_title">
        <input type="text" placeholder="Data Name" name="data_value[]" class="data_value">
    </div>`;
}

document.getElementById("p_p").onchange = function(){
    document.getElementById("img_p").src = URL.createObjectURL(p_p.files[0]); // Preview new image
}

function add_new_p() {
    const new_p = document.getElementsByClassName('new')[0];

    new_p.style.display = 'block';
}

document.addEventListener("keydown", (e) => {
    if (e.keyCode == 27) {
        const new_p = document.getElementsByClassName('new')[0];

        new_p.style.display = 'none';
    }
})

function save_() {
    var form_ = new FormData($("#product_form")[0]);
    $.ajax({
        type : 'POST',
        url  : '/let.php',
        data : form_,
        processData: false,
        contentType: false,
        success : function(response){
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "Product Added",
                    icon: "success",
                }).then(value => {
                    this.reload();
                });
            } else {
                swal({
                    title: "Error",
                    text: response,
                    icon: "Error",
                });
            }
        }
    });
}

const remove_product = document.getElementsByClassName('remove');


for (let i = 0; remove_product.length > i; i++) {
    remove_product[i].onclick = function () {

        let selected_Row = this.parentNode.parentNode;

        $.ajax({
            type : 'POST',
            url  : '/let.php?remove',
            data : {p_id: this.dataset.postId},
            success : function(response){
                if (response === "success") {
                    swal({
                        title: "Success",
                        text: "Product Removed",
                        icon: "success",
                    }).then(value => {
                        selected_Row.remove();
                    });
                } else {
                    swal({
                        title: "Error",
                        text: "Something Went Wrong!",
                        icon: "Error",
                    });
                }
            }
        });
    }
}

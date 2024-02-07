const remove_user = document.getElementsByClassName('remove');
const disable_user = document.getElementsByClassName('disable');

for (let i = 0; i < remove_user.length; i++) {
    remove_user[i].onclick = function () {
        // console.log(this.dataset.userId);
        let selected_Row = this.parentNode.parentNode.parentNode;
        $.ajax({
            type : 'POST',
            url  : '/edit.php?remove',
            data : {u_id: this.dataset.userId},
            success : function(response){
                if (response === "success") {
                    swal({
                        title: "Success",
                        text: "User Removed",
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

for (let j = 0; j < disable_user.length; j++) {
    disable_user[j].onclick = function () {
        // console.log(this.dataset.userId);
        let current_button = this;
        $.ajax({
            type : 'POST',
            url  : '/edit.php?disable',
            data : {u_id: this.dataset.userId},
            success : function(response){
                if (response === "Enabled") {
                    swal({
                        title: "Enabled",
                        text: "Account is Active Now.",
                        icon: "success",
                    }).then(value => {
                        current_button.innerText = 'Disable';
                    });
                } else if (response === "Disabled") {
                    swal({
                        title: "Disabled",
                        text: "Account is Disabled now.",
                        icon: "success",
                    }).then(value => {
                        current_button.innerText = 'Enable';
                    });
                }else {
                    swal({
                        title: "Error",
                        text: "Something went wrong.",
                        icon: "error",
                    });
                }
            }
        });
    }
}
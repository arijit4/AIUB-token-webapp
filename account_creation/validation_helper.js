function validateDets() {
    let name = document.getElementById('name').value
    let id = document.getElementById('id').value
    let pass = document.getElementById('pass').value
    let cpass = document.getElementById('cpass').value

    let errors = 0
    if (id.length !== "24-57775-2".length) {
        errors++;
        document.getElementById('err_id').innerText = "Invalid ID"
    } else {
        document.getElementById('err_id').innerText = ""
    }
    if (pass.length === 0) {
        errors++;
        document.getElementById('err_pass').innerText = "Password can't be empty"
    } else {
        document.getElementById('err_pass').innerText = ""
    }
    if (pass !== cpass) {
        document.getElementById('err_cpass').innerText = "Passwords do not match"
    } else {
        document.getElementById('err_cpass').innerText = ""
    }
    return (errors <= 0);
}
function validateDets() {
    // Use RegExp objects and anchors so the whole string must match
    let student_account_pattern = /^\d{2}-\d{5}-[1-3]$/;
    let teacher_account_pattern = /^\d{4}-\d{4}-[1-3]$/;

    let name = document.getElementById('name').value
    let id = document.getElementById('id').value
    let pass = document.getElementById('pass').value
    let cpass = document.getElementById('cpass').value

    let errors = 0
    // Invalid if it matches neither the student nor the teacher pattern
    if (!student_account_pattern.test(id) && !teacher_account_pattern.test(id)) {
        errors++;
        document.getElementById('err_id').innerText = "Invalid ID";
    } else {
        document.getElementById('err_id').innerText = "";
    }

    if (pass.length === 0) {
        errors++;
        document.getElementById('err_pass').innerText = "Password can't be empty";
    } else {
        document.getElementById('err_pass').innerText = "";
    }

    if (pass !== cpass) {
        document.getElementById('err_cpass').innerText = "Passwords do not match";
    } else {
        document.getElementById('err_cpass').innerText = "";
    }
    return (errors <= 0);
}
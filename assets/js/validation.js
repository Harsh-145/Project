// Form validation utilities
const FormValidator = {
    validateUsername: (username) => {
        if (username.trim().length === 0) {
            return { valid: false, message: 'Username is required' };
        }
        if (username.trim().length < 3) {
            return { valid: false, message: 'Username must be at least 3 characters' };
        }
        return { valid: true };
    },

    validateEmail: (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return { valid: false, message: 'Please enter a valid email' };
        }
        return { valid: true };
    },

    validatePassword: (password) => {
        if (password.length === 0) {
            return { valid: false, message: 'Password is required' };
        }
        if (password.length < 4) {
            return { valid: false, message: 'Password must be at least 4 characters' };
        }
        return { valid: true };
    },

    validatePhone: (phone) => {
        const phoneRegex = /^\d{10,}$/;
        if (!phoneRegex.test(phone.replace(/[^\d]/g, ''))) {
            return { valid: false, message: 'Phone must be at least 10 digits' };
        }
        return { valid: true };
    },

    validateSemester: (semester) => {
        const sem = parseInt(semester);
        if (isNaN(sem) || sem <= 0) {
            return { valid: false, message: 'Semester must be greater than 0' };
        }
        return { valid: true };
    },

    validateDate: (dateString) => {
        const date = new Date(dateString);
        const today = new Date();
        if (isNaN(date.getTime())) {
            return { valid: false, message: 'Invalid date' };
        }
        if (date >= today) {
            return { valid: false, message: 'Date of birth must be in the past' };
        }
        return { valid: true };
    }
};

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username')?.value.trim();
            const password = document.getElementById('password')?.value;

            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    }

    // Register form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username')?.value.trim();
            const email = document.getElementById('email')?.value.trim();
            const password = document.getElementById('password')?.value;

            let isValid = true;
            let errorMessages = [];

            const usernameValidation = FormValidator.validateUsername(username);
            if (!usernameValidation.valid) {
                isValid = false;
                errorMessages.push(usernameValidation.message);
            }

            const emailValidation = FormValidator.validateEmail(email);
            if (!emailValidation.valid) {
                isValid = false;
                errorMessages.push(emailValidation.message);
            }

            const passwordValidation = FormValidator.validatePassword(password);
            if (!passwordValidation.valid) {
                isValid = false;
                errorMessages.push(passwordValidation.message);
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }

    // Add student form validation
    const addStudentForm = document.getElementById('addStudentForm');
    if (addStudentForm) {
        addStudentForm.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone')?.value.trim();
            const sem = document.getElementById('sem')?.value;
            const dob = document.getElementById('dob')?.value;

            let isValid = true;
            let errorMessages = [];

            if (phone) {
                const phoneValidation = FormValidator.validatePhone(phone);
                if (!phoneValidation.valid) {
                    isValid = false;
                    errorMessages.push(phoneValidation.message);
                }
            }

            if (sem) {
                const semValidation = FormValidator.validateSemester(sem);
                if (!semValidation.valid) {
                    isValid = false;
                    errorMessages.push(semValidation.message);
                }
            }

            if (dob) {
                const dateValidation = FormValidator.validateDate(dob);
                if (!dateValidation.valid) {
                    isValid = false;
                    errorMessages.push(dateValidation.message);
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }

    // Update semester form validation
    const updateSemesterForm = document.getElementById('updateSemesterForm');
    if (updateSemesterForm) {
        updateSemesterForm.addEventListener('submit', function(e) {
            const sem = document.getElementById('sem')?.value;

            const semValidation = FormValidator.validateSemester(sem);
            if (!semValidation.valid) {
                e.preventDefault();
                alert(semValidation.message);
            }
        });
    }

    // Delete student form validation
    const deleteStudentForm = document.getElementById('deleteStudentForm');
    if (deleteStudentForm) {
        deleteStudentForm.addEventListener('submit', function(e) {
            const enrollmentNo = document.getElementById('enrollment_no')?.value.trim();

            if (!enrollmentNo) {
                e.preventDefault();
                alert('Please enter enrollment number');
            }

            const confirmed = confirm('Are you sure you want to delete this student? This action cannot be undone.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    }
});

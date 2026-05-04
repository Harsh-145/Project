document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('studentsTableBody');
    const status = document.getElementById('statusMessage');
    const loading = document.getElementById('loadingMessage');

    const showStatus = (type, message) => {
        if (!status) {
            return;
        }
        status.className = type === 'error' ? 'alert alert-error' : 'alert alert-info';
        status.textContent = message;
    };

    fetch('view-students.php')
        .then(function(response) {
            return response.json().then(function(data) {
                return { status: response.status, data };
            });
        })
        .then(function(result) {
            if (loading) {
                loading.style.display = 'none';
            }

            if (!result.data || !result.data.success) {
                showStatus('error', result.data && result.data.message ? result.data.message : 'Unable to load students.');
                return;
            }

            const students = result.data.students || [];
            if (students.length === 0) {
                showStatus('info', 'No students found in the system.');
                return;
            }

            students.forEach(function(student) {
                const row = document.createElement('tr');

                const cells = [
                    student.enrollment_no,
                    student.full_name,
                    student.gender,
                    student.dob,
                    student.phone,
                    student.email || '-',
                    student.room_no,
                    student.sem
                ];

                cells.forEach(function(value) {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    row.appendChild(cell);
                });

                tbody.appendChild(row);
            });
        })
        .catch(function() {
            if (loading) {
                loading.style.display = 'none';
            }
            showStatus('error', 'Unable to load students.');
        });
});

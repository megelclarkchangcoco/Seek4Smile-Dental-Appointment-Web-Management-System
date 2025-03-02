        // Tab switching function exposed globally
        function showTabContent(contentId) {
            const contents = document.querySelectorAll('.content > div');
            contents.forEach(content => content.style.display = 'none');
            const selectedContent = document.querySelector(`.${contentId}`);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
            const navLinks = document.querySelectorAll('#tabs .sub-navigation a');
            navLinks.forEach(link => link.classList.remove('active'));
            const clickedLink = document.querySelector(`#tabs .sub-navigation a[onclick="showTabContent('${contentId}')"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
        }
        window.showTabContent = showTabContent;
        document.addEventListener("DOMContentLoaded", function () {
            showTabContent('overview-content');
        });
        
        // Section toggling for patient list vs. detail view
        document.addEventListener("DOMContentLoaded", function () {
            function showRightPanelSection(sectionId) {
                const content = document.getElementById("content");
                const patientSection = document.getElementById("patient-section");
                if (sectionId === "patient-section") {
                    if (content) { content.style.display = "none"; }
                    if (patientSection) { patientSection.style.display = "block"; }
                } else {
                    if (content) { content.style.display = "block"; }
                    if (patientSection) { patientSection.style.display = "none"; }
                }
            }
            document.querySelectorAll(".close").forEach(closeBtn => {
                closeBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    showRightPanelSection("content");
                });
            });
            document.querySelectorAll(".view_btn").forEach(viewBtn => {
                viewBtn.addEventListener("click", function () {
                    showRightPanelSection("patient-section");
                });
            });
            if (localStorage.getItem("patientSectionVisible") !== "true") {
                const patientLink = document.querySelector(".sub-navigation a[href='#'][onclick*='patient-section']");
                if (patientLink) patientLink.style.display = "none";
                showRightPanelSection("content");
            }
            window.showRightPanelSection = showRightPanelSection;
        });
        
        // Chart rendering
        document.addEventListener('DOMContentLoaded', function () {
            var canvas = document.getElementById('appointmentPieChart');
            if (canvas) {
                var ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Completed Appointments", "Penalty Appointments"],
                        datasets: [{
                            data: [<?= $totalCompletedAppointments ?? 0 ?>, <?= $totalPenaltyAppointments ?? 0 ?>],
                            backgroundColor: ["#007bff", "#c97a33"],
                            hoverBackgroundColor: ["#0056b3", "#a15e28"]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
        
        // Modal functions
        function openUploadModal() {
            document.getElementById("uploadModal").style.display = "block";
        }
        function closeUploadModal() {
            document.getElementById("uploadModal").style.display = "none";
        }
        window.onclick = function(event) {
            var modal = document.getElementById("uploadModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
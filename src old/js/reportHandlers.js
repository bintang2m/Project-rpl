// assets/js/reportHandlers.js

document.addEventListener("DOMContentLoaded", function () {
  // Handler Laporan Harian
  const dailyForm = document.getElementById("dailyReportForm");
  if (dailyForm) {
    document
      .getElementById("saveDailyReport")
      .addEventListener("click", function () {
        submitReportForm(dailyForm, "save_daily_report.php");
      });
  }

  // Handler Laporan Mingguan
  const weeklyForm = document.getElementById("weeklyReportForm");
  if (weeklyForm) {
    document
      .getElementById("saveWeeklyReport")
      .addEventListener("click", function () {
        submitReportForm(weeklyForm, "save_weekly_report.php");
      });
  }

  // Handler Laporan Bulanan
  const monthlyForm = document.getElementById("monthlyReportForm");
  if (monthlyForm) {
    document
      .getElementById("saveMonthlyReport")
      .addEventListener("click", function () {
        submitReportForm(monthlyForm, "save_monthly_report.php");
      });
  }
});

function submitReportForm(formElement, endpoint) {
  const formData = new FormData(formElement);
  const submitBtn = formElement.querySelector('button[type="button"]');
  const originalBtnText = submitBtn.innerHTML;

  // Tampilkan loading
  submitBtn.disabled = true;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

  fetch(endpoint, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        // Tutup modal dan reset form
        bootstrap.Modal.getInstance(formElement.closest(".modal")).hide();
        formElement.reset();

        // Refresh data laporan jika diperlukan
        if (typeof refreshReportTable === "function") {
          refreshReportTable();
        }
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan jaringan");
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    });
}

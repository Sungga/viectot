// Thêm hiệu ứng load khi chuyển trang (submit form, click link, reload)

const loadingContainer = document.querySelector('.loading__container');

document.addEventListener('DOMContentLoaded', function () {
    // Khi trang sắp bị unload (reload hoặc chuyển trang)
    window.addEventListener('beforeunload', function () {
        loadingContainer.style.display = 'flex'; // Hiển thị hiệu ứng load
    });

    // Khi form được submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function () {
            loadingContainer.style.display = 'flex'; // Hiển thị hiệu ứng load khi submit form
        });
    });

    // Khi click vào liên kết <a> có href hợp lệ
    const links = document.querySelectorAll('a');
    links.forEach(link => {
        const href = link.getAttribute('href');
        // Chỉ áp dụng với những link thực sự chuyển trang
        if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
            link.addEventListener('click', function () {
                loadingContainer.style.display = 'flex';
            });
        }
    });
});

// --------------------------<< JS ALERT >>-------------------------
const FancyAlert = (function () {
  let currentAlert = null;

  function show({ msg = "Success", type = "success", timeout = 4000 }) {
    if (currentAlert) {
      hide();
    }

    const alertBox = document.createElement("div");
    alertBox.className = `fancy-alert ${type}`;
    alertBox.innerHTML = `
      <span>${msg}</span>
      <span class="fancy-alert--close">&times;</span>
    `;

    document.body.appendChild(alertBox);
    currentAlert = alertBox;

    setTimeout(() => {
      alertBox.classList.add("active");
    }, 10);

    // Auto close
    setTimeout(() => {
      hide();
    }, timeout);

    // Manual close
    alertBox.querySelector(".fancy-alert--close").addEventListener("click", hide);
  }

  function hide() {
    if (currentAlert) {
      currentAlert.classList.remove("active");
      setTimeout(() => {
        currentAlert.remove();
        currentAlert = null;
      }, 300);
    }
  }

  return { show, hide };
})();
  
if (window._alertErrorMsg) {
  FancyAlert.show({
      msg: window._alertErrorMsg,
      type: 'error',
  });
}
if (window._alertInfoMsg) {
  FancyAlert.show({
      msg: window._alertInfoMsg,
      type: 'info',
  });
}
if (window._alertSuccessMsg) {
  FancyAlert.show({
      msg: window._alertSuccessMsg,
      type: 'success',
  });
}


// js dashboard
const dashboard = document.querySelector('.dashboard');
const slider = document.querySelector('.slider');
if(slider != null) {
  let checkShowDashboard = 0;
  const totalJobToday = document.querySelector('.slider__workMarket--newJob span');
  const totalJob = document.querySelector('.slider__workMarket--totalJob span');
  // totalJobToday.innerHTML = 0;
  $(document).ready(function () {
      $.ajax({
          url: '/getTotalJobToday',
          method: 'get',
          data: {
              _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            totalJobToday.innerHTML = response.total;
          },
          error: function (xhr) {
              alert('Đã xảy ra lỗi khi gợi ý: ' + xhr.responseText);
          }
      });
      $.ajax({
          url: '/getTotalJob',
          method: 'get',
          data: {
              _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            totalJob.innerHTML = response.total;
          },
          error: function (xhr) {
              alert('Đã xảy ra lỗi khi gợi ý: ' + xhr.responseText);
          }
      });
  });
  // $('.slider__workMarket--seeMore p').click(function () {
  //   $.ajax({
  //         url: '/dashboard',
  //         method: 'get',
  //         data: {
  //             _token: '{{ csrf_token() }}'
  //         },
  //         success: function (response) {
  //           $('.dashboard__content').html(response);
  //           // totalJob.innerHTML = response.total;
  //           // console.log(response);
  //         },
  //         error: function (xhr) {
  //             alert('Đã xảy ra lỗi khi gợi ý: ' + xhr.responseText);
  //         }
  //     });
  // });
  $('.slider__workMarket--seeMore p').click(function () {
    if(checkShowDashboard < 1) {
      checkShowDashboard++;
      $.ajax({
          url: '/dashboard',
          method: 'get',
          success: function (response) {
            // console.log('HTML nhận được:', response);
            $('.dashboard__content').html(response); // response là HTML
          },
          error: function (xhr) {
            alert('Lỗi: ' + xhr.responseText);
            console.log('Lỗi: ' + xhr.responseText);
          }
      });
    }
  });
}

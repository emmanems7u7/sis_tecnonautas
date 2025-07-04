"use strict";
(function() {
  var isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

  if (isWindows) {
    // if we are on windows OS we activate the perfectScrollbar function
    if (document.getElementsByClassName('main-content')[0]) {
      var mainpanel = document.querySelector('.main-content');
      var ps = new PerfectScrollbar(mainpanel);
    };

    if (document.getElementsByClassName('sidenav')[0]) {
      var sidebar = document.querySelector('.sidenav');
      var ps1 = new PerfectScrollbar(sidebar);
    };

    if (document.getElementsByClassName('navbar-collapse')[0]) {
      var fixedplugin = document.querySelector('.navbar:not(.navbar-expand-lg) .navbar-collapse');
      var ps2 = new PerfectScrollbar(fixedplugin);
    };

    if (document.getElementsByClassName('fixed-plugin')[0]) {
      var fixedplugin = document.querySelector('.fixed-plugin');
      var ps3 = new PerfectScrollbar(fixedplugin);
    };
  };
})();

// Verify navbar blur on scroll
if (document.getElementById('navbarBlur') && document.getElementById('navbarBlur').getAttribute('data-scroll') == 'true') {
  navbarBlurOnScroll('navbarBlur');
}

// initialization of Tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// when input is focused add focused class for style
function focused(el) {
  if (el.parentElement.classList.contains('input-group')) {
    el.parentElement.classList.add('focused');
  }
}

// when input is focused remove focused class for style
function defocused(el) {
  if (el.parentElement.classList.contains('input-group')) {
    el.parentElement.classList.remove('focused');
  }
}

// helper for adding on all elements multiple attributes
function setAttributes(el, options) {
  Object.keys(options).forEach(function(attr) {
    el.setAttribute(attr, options[attr]);
  })
}

// adding on inputs attributes for calling the focused and defocused functions
if (document.querySelectorAll('.input-group').length != 0) {
  var allInputs = document.querySelectorAll('input.form-control');
  allInputs.forEach(el => setAttributes(el, {
    "onfocus": "focused(this)",
    "onfocusout": "defocused(this)"
  }));
}


// Fixed Plugin

if (document.querySelector('.fixed-plugin')) {
  var fixedPlugin = document.querySelector('.fixed-plugin');
  var fixedPlugin = document.querySelector('.fixed-plugin');
  var fixedPluginButton = document.querySelector('.fixed-plugin-button');
  var fixedPluginButtonNav = document.querySelector('.fixed-plugin-button-nav');
  var fixedPluginCard = document.querySelector('.fixed-plugin .card');
  var fixedPluginCloseButton = document.querySelectorAll('.fixed-plugin-close-button');
  var navbar = document.getElementById('navbarBlur');
  var buttonNavbarFixed = document.getElementById('navbarFixed');

  if (fixedPluginButton) {
    fixedPluginButton.onclick = function() {
      if (!fixedPlugin.classList.contains('show')) {
        fixedPlugin.classList.add('show');
      } else {
        fixedPlugin.classList.remove('show');
      }
    }
  }

  if (fixedPluginButtonNav) {
    fixedPluginButtonNav.onclick = function() {
      if (!fixedPlugin.classList.contains('show')) {
        fixedPlugin.classList.add('show');
      } else {
        fixedPlugin.classList.remove('show');
      }
    }
  }

  fixedPluginCloseButton.forEach(function(el) {
    el.onclick = function() {
      fixedPlugin.classList.remove('show');
    }
  })

  document.querySelector('body').onclick = function(e) {
    if (e.target != fixedPluginButton && e.target != fixedPluginButtonNav && e.target.closest('.fixed-plugin .card') != fixedPluginCard) {
      fixedPlugin.classList.remove('show');
    }
  }

  if (navbar) {
    if (navbar.getAttribute('data-scroll') == 'true' && buttonNavbarFixed) {
      buttonNavbarFixed.setAttribute("checked", "true");
    }
  }

}

// Set Sidebar Type


// Set Navbar Fixed
function navbarFixed(el) {
  let classes = ['position-sticky', 'blur', 'shadow-blur', 'mt-4', 'left-auto', 'top-1', 'z-index-sticky'];
  const navbar = document.getElementById('navbarBlur');

  if (!el.getAttribute("checked")) {
    navbar.classList.add(...classes);
    navbar.setAttribute('navbar-scroll', 'true');
    navbarBlurOnScroll('navbarBlur');
    el.setAttribute("checked", "true");
  } else {
    navbar.classList.remove(...classes);
    navbar.setAttribute('navbar-scroll', 'false');
    navbarBlurOnScroll('navbarBlur');
    el.removeAttribute("checked");
  }
};


// Set Navbar Minimized

function navbarMinimize(e) {
  var t = document.getElementsByClassName("g-sidenav-show")[0];
  e.getAttribute("checked") ? (t.classList.remove("g-sidenav-hidden"), t.classList.add("g-sidenav-pinned"), e.removeAttribute("checked")) : (t.classList.remove("g-sidenav-pinned"), t.classList.add("g-sidenav-hidden"), e.setAttribute("checked", "true"))
}

// Navbar blur on scroll
function navbarBlurOnScroll(id) {
  const navbar = document.getElementById(id);
  let navbarScrollActive = navbar ? navbar.getAttribute("data-scroll") : false;
  let scrollDistance = 5;
  let classes = ['blur', 'shadow-blur', 'left-auto'];
  let toggleClasses = ['shadow-none'];

  if (navbarScrollActive == 'true') {
    window.onscroll = debounce(function() {
      if (window.scrollY > scrollDistance) {
        blurNavbar();
      } else {
        transparentNavbar();
      }
    }, 10);
  } else {
    window.onscroll = debounce(function() {
      transparentNavbar();
    }, 10);
  }

  var isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

  if (isWindows) {
    var content = document.querySelector('.main-content');
    if (navbarScrollActive == 'true') {
      content.addEventListener('ps-scroll-y', debounce(function() {
        if (content.scrollTop > scrollDistance) {
          blurNavbar();
        } else {
          transparentNavbar();
        }
      }, 10));
    } else {
      content.addEventListener('ps-scroll-y', debounce(function() {
        transparentNavbar();
      }, 10));
    }
  }

  function blurNavbar() {
    navbar.classList.add(...classes)
    navbar.classList.remove(...toggleClasses)

    toggleNavLinksColor('blur');
  }

  function transparentNavbar() {
    navbar.classList.remove(...classes)
    navbar.classList.add(...toggleClasses)

    toggleNavLinksColor('transparent');
  }

  function toggleNavLinksColor(type) {
    let navLinks = document.querySelectorAll('.navbar-main .nav-link')
    let navLinksToggler = document.querySelectorAll('.navbar-main .sidenav-toggler-line')

    if (type === "blur") {
      navLinks.forEach(element => {
        element.classList.remove('text-body')
      });

      navLinksToggler.forEach(element => {
        element.classList.add('bg-dark')
      });
    } else if (type === "transparent") {
      navLinks.forEach(element => {
        element.classList.add('text-body')
      });

      navLinksToggler.forEach(element => {
        element.classList.remove('bg-dark')
      });
    }
  }
}

// Debounce Function
// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

// initialization of Toasts
document.addEventListener("DOMContentLoaded", function() {
  var toastElList = [].slice.call(document.querySelectorAll(".toast"));

  var toastList = toastElList.map(function(toastEl) {
    return new bootstrap.Toast(toastEl);
  });

  var toastButtonList = [].slice.call(document.querySelectorAll(".toast-btn"));

  toastButtonList.map(function(toastButtonEl) {
    toastButtonEl.addEventListener("click", function() {
      var toastToTrigger = document.getElementById(toastButtonEl.dataset.target);

      if (toastToTrigger) {
        var toast = bootstrap.Toast.getInstance(toastToTrigger);
        toast.show();
      }
    });
  });
});

// Tabs navigation

var total = document.querySelectorAll('.nav-pills');

function initNavs() {
  total.forEach(function(item, i) {
    var moving_div = document.createElement('div');
    var first_li = item.querySelector('li:first-child .nav-link');
    var tab = first_li.cloneNode();
    tab.innerHTML = "-";

    moving_div.classList.add('moving-tab', 'position-absolute', 'nav-link');
    moving_div.appendChild(tab);
    item.appendChild(moving_div);

    var list_length = item.getElementsByTagName("li").length;

    moving_div.style.padding = '0px';
    moving_div.style.width = item.querySelector('li:nth-child(1)').offsetWidth + 'px';
    moving_div.style.transform = 'translate3d(0px, 0px, 0px)';
    moving_div.style.transition = '.5s ease';

    item.onmouseover = function(event) {
      let target = getEventTarget(event);
      let li = target.closest('li'); // get reference
      if (li) {
        let nodes = Array.from(li.closest('ul').children); // get array
        let index = nodes.indexOf(li) + 1;
        item.querySelector('li:nth-child(' + index + ') .nav-link').onclick = function() {
          moving_div = item.querySelector('.moving-tab');
          let sum = 0;
          if (item.classList.contains('flex-column')) {
            for (var j = 1; j <= nodes.indexOf(li); j++) {
              sum += item.querySelector('li:nth-child(' + j + ')').offsetHeight;
            }
            moving_div.style.transform = 'translate3d(0px,' + sum + 'px, 0px)';
            moving_div.style.height = item.querySelector('li:nth-child(' + j + ')').offsetHeight;
          } else {
            for (var j = 1; j <= nodes.indexOf(li); j++) {
              sum += item.querySelector('li:nth-child(' + j + ')').offsetWidth;
            }
            moving_div.style.transform = 'translate3d(' + sum + 'px, 0px, 0px)';
            moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';
          }
        }
      }
    }
  });
}

setTimeout(function() {
  initNavs();
}, 100);

// Tabs navigation resize

window.addEventListener('resize', function(event) {
  total.forEach(function(item, i) {
    item.querySelector('.moving-tab').remove();
    var moving_div = document.createElement('div');
    var tab = item.querySelector(".nav-link.active").cloneNode();
    tab.innerHTML = "-";

    moving_div.classList.add('moving-tab', 'position-absolute', 'nav-link');
    moving_div.appendChild(tab);

    item.appendChild(moving_div);

    moving_div.style.padding = '0px';
    moving_div.style.transition = '.5s ease';

    let li = item.querySelector(".nav-link.active").parentElement;

    if (li) {
      let nodes = Array.from(li.closest('ul').children); // get array
      let index = nodes.indexOf(li) + 1;

      let sum = 0;
      if (item.classList.contains('flex-column')) {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetHeight;
        }
        moving_div.style.transform = 'translate3d(0px,' + sum + 'px, 0px)';
        moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';
        moving_div.style.height = item.querySelector('li:nth-child(' + j + ')').offsetHeight;
      } else {
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetWidth;
        }
        moving_div.style.transform = 'translate3d(' + sum + 'px, 0px, 0px)';
        moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';

      }
    }
  });

  if (window.innerWidth < 991) {
    total.forEach(function(item, i) {
      if (!item.classList.contains('flex-column')) {
        item.classList.remove('flex-row');
        item.classList.add('flex-column', 'on-resize');
        let li = item.querySelector(".nav-link.active").parentElement;
        let nodes = Array.from(li.closest('ul').children); // get array
        let index = nodes.indexOf(li) + 1;
        let sum = 0;
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetHeight;
        }
        var moving_div = document.querySelector('.moving-tab');
        moving_div.style.width = item.querySelector('li:nth-child(1)').offsetWidth + 'px';
        moving_div.style.transform = 'translate3d(0px,' + sum + 'px, 0px)';

      }
    });
  } else {
    total.forEach(function(item, i) {
      if (item.classList.contains('on-resize')) {
        item.classList.remove('flex-column', 'on-resize');
        item.classList.add('flex-row');
        let li = item.querySelector(".nav-link.active").parentElement;
        let nodes = Array.from(li.closest('ul').children); // get array
        let index = nodes.indexOf(li) + 1;
        let sum = 0;
        for (var j = 1; j <= nodes.indexOf(li); j++) {
          sum += item.querySelector('li:nth-child(' + j + ')').offsetWidth;
        }
        var moving_div = document.querySelector('.moving-tab');
        moving_div.style.transform = 'translate3d(' + sum + 'px, 0px, 0px)';
        moving_div.style.width = item.querySelector('li:nth-child(' + index + ')').offsetWidth + 'px';
      }
    })
  }
});

// Function to remove flex row on mobile devices
if (window.innerWidth < 991) {
  total.forEach(function(item, i) {
    if (item.classList.contains('flex-row')) {
      item.classList.remove('flex-row');
      item.classList.add('flex-column', 'on-resize');
    }
  });
}

function getEventTarget(e) {
  e = e || window.event;
  return e.target || e.srcElement;
}

// End tabs navigation

window.onload = function() {
  // Material Design Input function
  var inputs = document.querySelectorAll('input');

  for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('focus', function(e) {
      this.parentElement.classList.add('is-focused');
    }, false);

    inputs[i].onkeyup = function(e) {
      if (this.value != "") {
        this.parentElement.classList.add('is-filled');
      } else {
        this.parentElement.classList.remove('is-filled');
      }
    };

    inputs[i].addEventListener('focusout', function(e) {
      if (this.value != "") {
        this.parentElement.classList.add('is-filled');
      }
      this.parentElement.classList.remove('is-focused');
    }, false);
  }

  // Ripple Effect
  var ripples = document.querySelectorAll('.btn');

  for (var i = 0; i < ripples.length; i++) {
    ripples[i].addEventListener('click', function(e) {
      var targetEl = e.target;
      var rippleDiv = targetEl.querySelector('.ripple');

      rippleDiv = document.createElement('span');
      rippleDiv.classList.add('ripple');
      rippleDiv.style.width = rippleDiv.style.height = Math.max(targetEl.offsetWidth, targetEl.offsetHeight) + 'px';
      targetEl.appendChild(rippleDiv);

      rippleDiv.style.left = (e.offsetX - rippleDiv.offsetWidth / 2) + 'px';
      rippleDiv.style.top = (e.offsetY - rippleDiv.offsetHeight / 2) + 'px';
      rippleDiv.classList.add('ripple');
      setTimeout(function() {
        rippleDiv.parentElement.removeChild(rippleDiv);
      }, 600);
    }, false);
  }
};

// Toggle Sidenav
const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
const iconSidenav = document.getElementById('iconSidenav');
const sidenav = document.getElementById('sidenav-main');
let body = document.getElementsByTagName('body')[0];
let className = 'g-sidenav-pinned';

if (iconNavbarSidenav) {
  iconNavbarSidenav.addEventListener("click", toggleSidenav);
}

if (iconSidenav) {
  iconSidenav.addEventListener("click", toggleSidenav);
}

function toggleSidenav() {
  if (body.classList.contains(className)) {
    body.classList.remove(className);
    setTimeout(function() {
    
    }, 100);
    sidenav.classList.remove('bg-transparent');

  } else {
    body.classList.add(className);
  
    sidenav.classList.remove('bg-transparent');
    iconSidenav.classList.remove('d-none');
  }
}

// Resize navbar color depends on configurator active type of sidenav

let referenceButtons = document.querySelector('[data-class]');

if (sidenav) {
  window.addEventListener("resize", navbarColorOnResize);

  function navbarColorOnResize() {
    if (window.innerWidth > 1200) {
      if (referenceButtons?.classList.contains('active') && referenceButtons?.getAttribute('data-class') === 'bg-transparent') {
        sidenav.classList.remove('bg-white');
      } else {
      
      }
    } else {
     
    }
  }
}

// Deactivate sidenav type buttons on resize and small screens
window.addEventListener("resize", sidenavTypeOnResize);
window.addEventListener("load", sidenavTypeOnResize);

function sidenavTypeOnResize() {
  let elements = document.querySelectorAll('[onclick="sidebarType(this)"]');
  if (window.innerWidth < 1200) {
    elements.forEach(function(el) {
     
    });
  } else {
    elements.forEach(function(el) {
      el.classList.remove('disabled');
    });
  }
}


// Light Mode / Dark Mode


// side bullets

const indicators = document.querySelectorAll(".indicator");
const sections = document.querySelectorAll("section");

if (indicators) {
  const resetCurrentActiveIndicator = () => {
    const activeIndicator = document.querySelector(".indicator.active");
    if (activeIndicator) {
      activeIndicator.classList.remove("active");
    }
  };

  const onSectionLeavesViewport = (section) => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            resetCurrentActiveIndicator();
            const element = entry.target;
            const indicator = document.querySelector(`a[href='#${element.id}']`);
            if (indicator) {
              indicator.classList.add("active");
            }
            return;
          }
        });
      }, {
        root: null,
        rootMargin: "0px",
        threshold: 0.75
      }
    );
    observer.observe(section);
  };

  indicators.forEach((indicator) => {
    indicator.addEventListener("click", function(event) {
      event.preventDefault();
      document
        .querySelector(this.getAttribute("href"))
        .scrollIntoView({
          behavior: "smooth"
        });
      resetCurrentActiveIndicator();
      this.classList.add("active");
    });
  });

  sections.forEach(onSectionLeavesViewport);
}



document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('notificationTrigger');
    const box = document.getElementById('notificationBox');

    trigger.addEventListener('click', function (e) {
        e.stopPropagation(); // Evita que el clic se propague y cierre inmediatamente
        box.classList.toggle('active');
    });

    // Cerrar si se hace clic fuera
    document.addEventListener('click', function () {
        box.classList.remove('active');
    });

    // Evitar que el clic dentro del box lo cierre
    box.addEventListener('click', function (e) {
        e.stopPropagation();
    });
});

function NotificacionLeida(event, notificacion_id) {
    fetch('/notification/' + notificacion_id + '/markAsRead', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al marcar la notificación como leída');
            }
            return response.json();
        })
        .then(data => {

            var url = event.target.href;
            window.location.href = url;
        })
        .catch(error => {
            console.error('Error al marcar la notificación como leída:', error);

        });
}

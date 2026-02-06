const setHeaderHeight = () => {
  const header = document.querySelector(".header-main");
  const headerHeight = header?.offsetHeight;

  document.documentElement.style.setProperty("--header-height", `${headerHeight}px`);
}

// function handleCustomAccordion() {
//   const wrappers = document.querySelectorAll(".custom-accordion-wrapper");
//   if (!wrappers) return;

//   // Close all non-protected accordions except the one to exclude
//   const closeAll = (excludeWrapper = null) => {
//     wrappers.forEach((wrapper) => {
//       // Skip if this is the wrapper to exclude or has data-keep-open
//       if (wrapper === excludeWrapper || wrapper.hasAttribute("data-keep-open"))
//         return;

//       closeAccordion(wrapper);
//     });
//   };

//   // Close a specific accordion
//   const closeAccordion = (wrapper) => {
//     const accordionBody = wrapper.querySelector(".accordion-body");
//     const accordionBtn = wrapper.querySelector(".accordion-btn");
//     const accordionIcon = wrapper.querySelector(".accordion-icon");
//     const innerBody = accordionBody.querySelector(".inner-body");

//     accordionBody.style.maxHeight = "0px";
//     accordionBtn.classList.remove("open");
//     accordionBody.classList.remove("open");
//     accordionIcon?.classList.remove("rotate-180");
//     innerBody.classList.add("translate-y-2/4");
//   };

//   const getAccordionHeight = (accordionBody) => {

//     const scrollHeight = accordionBody.scrollHeight;
//     const style = new Object();

//     if (scrollHeight === 0) {
//       style.maxHeight = "fit-content";
//     } else {
//       style.maxHeight = `${scrollHeight+scrollHeight}px`;
//     }

//     return style;
//   };


// // Open a specific accordion
// const openAccordion = (wrapper) => {
//   const accordionBody = wrapper.querySelector(".accordion-body");
//   const accordionBtn = wrapper.querySelector(".accordion-btn");
//   const accordionIcon = wrapper.querySelector(".accordion-icon");
//   const innerBody = accordionBody.querySelector(".inner-body");

//   // Remove transform BEFORE calculating height
//   innerBody.classList.remove("translate-y-2/4");
  
//   accordionBody.removeAttribute("style");
//   const heightStyle = getAccordionHeight(accordionBody);
//   accordionBody.style.maxHeight = heightStyle.maxHeight;

//   accordionBtn.classList.add("open");
//   accordionBody.classList.add("open");
//   accordionIcon?.classList.add("rotate-180");
// };

//   // Initialize accordions
//   wrappers.forEach((wrapper) => {
//     const accordionBtn = wrapper.querySelector(".accordion-btn");
//     const accordionBody = wrapper.querySelector(".accordion-body");

//     // Open initially if specified
//     if (wrapper.hasAttribute("data-initially-open")) {
//       openAccordion(wrapper);
//     }

//     accordionBtn.addEventListener("click", () => {
//       const isOpen = accordionBody.classList.contains("open");

//       if (isOpen) {
//         // Always allow closing when directly clicked, even if protected
//         closeAccordion(wrapper);
//       } else {
//         // Open this one and close others (except protected ones)
//         closeAll(wrapper);
//         openAccordion(wrapper);
//       }
//     });
//   });
// }

function handleCustomAccordion() {
  const wrappers = document.querySelectorAll(".custom-accordion-wrapper");
  if (!wrappers) return;

  // Close all non-protected accordions except the one to exclude
  const closeAll = (excludeWrapper = null) => {
    wrappers.forEach((wrapper) => {
      // Skip if this is the wrapper to exclude or has data-keep-open
      if (wrapper === excludeWrapper || wrapper.hasAttribute("data-keep-open"))
        return;

      closeAccordion(wrapper);
    });
  };

  // Close a specific accordion
  const closeAccordion = (wrapper) => {
    const accordionBody = wrapper.querySelector(".accordion-body");
    const accordionBtn = wrapper.querySelector(".accordion-btn");
    const accordionIcon = wrapper.querySelector(".accordion-icon");
    const innerBody = accordionBody.querySelector(".inner-body");

    accordionBody.style.maxHeight = "0px";
    accordionBtn.classList.remove("open");
    accordionBody.classList.remove("open");
    accordionIcon?.classList.remove("rotate-180");
    innerBody.classList.add("translate-y-2/4");
  };

  const getAccordionHeight = (accordionBody) => {
    const scrollHeight = accordionBody.scrollHeight;
    const style = new Object();

    if (scrollHeight === 0) {
      style.maxHeight = "fit-content";
    } else {
      style.maxHeight = `${scrollHeight + scrollHeight}px`;
    }

    return style;
  };

  // Open a specific accordion
  const openAccordion = (wrapper) => {
    const accordionBody = wrapper.querySelector(".accordion-body");
    const accordionBtn = wrapper.querySelector(".accordion-btn");
    const accordionIcon = wrapper.querySelector(".accordion-icon");
    const innerBody = accordionBody.querySelector(".inner-body");

    // Remove transform BEFORE calculating height
    innerBody.classList.remove("translate-y-2/4");

    accordionBody.removeAttribute("style");
    const heightStyle = getAccordionHeight(accordionBody);
    accordionBody.style.maxHeight = heightStyle.maxHeight;

    accordionBtn.classList.add("open");
    accordionBody.classList.add("open");
    accordionIcon?.classList.add("rotate-180");
  };

  // Initialize accordions
  wrappers.forEach((wrapper) => {
    const accordionBtn = wrapper.querySelector(".accordion-btn");
    const accordionBody = wrapper.querySelector(".accordion-body");

    // Open initially if specified
    if (wrapper.hasAttribute("data-initially-open")) {
      openAccordion(wrapper);
    }

    accordionBtn.addEventListener("click", (e) => {
      // Check if the clicked element or any parent up to accordionBtn has data-no-toggle
      let target = e.target;
      let shouldPreventToggle = false;

      while (target && target !== accordionBtn) {
        if (target.hasAttribute("data-no-toggle")) {
          shouldPreventToggle = true;
          break;
        }
        target = target.parentElement;
      }

      // If data-no-toggle found, don't toggle the accordion
      if (shouldPreventToggle) return;

      const isOpen = accordionBody.classList.contains("open");

      if (isOpen) {
        // Always allow closing when directly clicked, even if protected
        closeAccordion(wrapper);
      } else {
        // Open this one and close others (except protected ones)
        closeAll(wrapper);
        openAccordion(wrapper);
      }
    });
  });
}

function handlePriceRangeSlider() {
  const rangeSliders = document.querySelectorAll(".range-slider");
  if (!rangeSliders) return;


  rangeSliders.forEach((rangeSlide) => {
    rangeSlide.classList.add("w-full")
    const slider = rangeSlide.querySelector("tc-range-slider");
    const lowerband = rangeSlide.querySelector(".lowerband span");
    const higherband = rangeSlide.querySelector(".higherband span");

    const updateValue = () => {
      lowerband.textContent = Math.round(slider.value1).toLocaleString();
      higherband.textContent = Math.round(slider.value2).toLocaleString();
    };

    updateValue();

    slider.addEventListener("change", updateValue);
  });
}

class CustomDropdown {
  constructor(selectElement, label = null) {
    this.label = label;
    this.selectElement = selectElement;
    this.createCustomDropdown();
    this.init();
  }

  createCustomDropdown() {
    const uniqueId =
      "custom-dropdown-" + Math.random().toString(36).slice(2, 11);
    const originalId = this.selectElement.getAttribute("id");

    if (originalId) {
      const existingDropdown = document.getElementById(
        `custom-dropdown-${originalId}`
      );
      if (
        existingDropdown &&
        existingDropdown.tagName.toLowerCase() !== "select"
      ) {
        existingDropdown.remove();
      }
    }

    this.selectClassList = this.selectElement.classList.contains(
      "custom-dropdown"
    )
      ? this.selectElement.className
      : `${this.selectElement.className} custom-dropdown`;

    this.selectElement.classList.add("hidden");
    this.wrapper = document.createElement("div");
    this.wrapper.className = this.selectClassList;
    this.wrapper.classList.remove("hidden");
    this.wrapper.id = originalId ? `custom-dropdown-${originalId}` : uniqueId;
    this.dropdownInput = document.createElement("button");
    this.dropdownInput.className = "dropdown-input";
    this.dropdownInput.setAttribute("type", "button");
    this.dropdownInput.setAttribute("aria-haspopup", "listbox");
    this.dropdownInput.setAttribute("aria-expanded", "false");

    this.textSpan = document.createElement("span");
    this.textSpan.className = "text";

    const icon = document.createElement("i");
    icon.className = "iconify icon";
    icon.setAttribute("data-icon", "line-md:chevron-down");
    icon.setAttribute("aria-hidden", "true");

    this.dropdownInput.appendChild(this.textSpan);
    this.dropdownInput.appendChild(icon);

    this.dropdownBody = document.createElement("div");
    this.dropdownBody.className = "dropdown-body";
    this.dropdownBody.setAttribute("role", "listbox");

    const ul = document.createElement("ul");
    ul.className = "w-full max-h-52 overflow-y-auto";

    this.options = Array.from(this.selectElement.options).map(
      (option, index) => {
        const li = document.createElement("li");
        li.className = "body-item";
        li.setAttribute("role", "option");
        li.setAttribute("tabindex", option.disabled ? "-1" : "0");
        if (option.disabled) {
          li.classList.add("disabled");
          li.setAttribute("aria-disabled", "true");
        } else {
          li.setAttribute("aria-selected", "false");
        }

        const optionText = document.createElement("span");
        optionText.className = "text";
        optionText.setAttribute("data-text", option.value);
        optionText.textContent = option.text;

        const tickIcon = document.createElement("i");
        tickIcon.className = "iconify tick-icon text-lg hidden";
        tickIcon.setAttribute("data-icon", "hugeicons:tick-02");
        tickIcon.setAttribute("aria-hidden", "true");

        li.appendChild(optionText);
        li.appendChild(tickIcon);
        ul.appendChild(li);

        return { element: li, option: option };
      }
    );

    this.dropdownBody.appendChild(ul);
    this.wrapper.appendChild(this.dropdownInput);
    this.wrapper.appendChild(this.dropdownBody);

    this.selectElement.parentNode.insertBefore(
      this.wrapper,
      this.selectElement.nextSibling
    );
  }

  init() {
    this.initializeDropdown();
    this.setupEventListeners();

    // If label passed :
    if (this.label) {
      this.label.addEventListener("click", (e) => {
        console.log("hello");
        e.stopPropagation();
        this.toggleDropdown();
      });
    }
  }

  toggleDropdown() {
    const { dropdownBody, dropdownInput, wrapper } = this;
    const isCurrentlyOpen =
      dropdownBody.classList.contains("open") ||
      dropdownBody.classList.contains("open-top");

    // Close all other dropdowns
    document.querySelectorAll(".custom-dropdown").forEach((dropdown) => {
      if (dropdown !== wrapper) {
        const body = dropdown.querySelector(".dropdown-body");
        const input = dropdown.querySelector(".dropdown-input");

        body?.classList.remove("open", "open-top");
        input?.classList.remove("open", "open-top");
        input?.setAttribute("aria-expanded", "false");
      }
    });

    // If already open, close and return early
    if (isCurrentlyOpen) {
      dropdownBody.classList.remove("open", "open-top");
      dropdownInput.classList.remove("open", "open-top");
      dropdownInput.setAttribute("aria-expanded", "false");
      return;
    }

    // Determine optimal position
    dropdownBody.classList.add("open");
    dropdownInput.classList.add("open");

    const { bottom } = dropdownBody.getBoundingClientRect();
    const viewportHeight = window.innerHeight;

    if (bottom > viewportHeight) {
      dropdownBody.classList.replace("open", "open-top");
      dropdownInput.classList.replace("open", "open-top");
    }

    dropdownInput.setAttribute("aria-expanded", "true");
  }

  initializeDropdown() {
    const selectedOption =
      this.selectElement.options[this.selectElement.selectedIndex];
    const bodyTickIcons = this.dropdownBody.querySelectorAll(".tick-icon");

    bodyTickIcons.forEach((icon) => icon.classList.add("hidden"));

    if (selectedOption) {
      const selectedValue = selectedOption.value;
      this.options.forEach((item, index) => {
        const itemValue = item.element
          .querySelector(".text")
          .getAttribute("data-text");
        if (
          itemValue === selectedValue &&
          !item.element.classList.contains("disabled")
        ) {
          const tickIcon = item.element.querySelector(".tick-icon");
          tickIcon.classList.remove("hidden");
          this.textSpan.textContent = selectedOption.text;
          this.dropdownInput.value = selectedValue;
          item.element.setAttribute("aria-selected", "true");
        } else {
          item.element.setAttribute("aria-selected", "false");
        }
      });
    } else {
      this.textSpan.textContent = "Select an Option";
      this.dropdownInput.value = "";
    }
  }

  setupEventListeners() {
    this.dropdownInput.addEventListener("click", (e) => {
      e.stopPropagation();
      this.toggleDropdown();
    });

    this.options.forEach((item, index) => {
      if (!item.option.disabled) {
        item.element.addEventListener("click", (e) => {
          e.stopPropagation();

          this.selectElement.selectedIndex = index;

          this.initializeDropdown();
          this.toggleDropdown();

          const event = new Event("change", { bubbles: true });
          this.selectElement.dispatchEvent(event);
        });
      }
    });

    // Keyboard navigation
    this.wrapper.addEventListener("keydown", (e) => {
      const isNotOpen =
        !this.dropdownBody.classList.contains("open") &&
        !this.dropdownBody.classList.contains("open-top");

      if (isNotOpen) return;

      const enabledItems = this.options.filter((item) => !item.option.disabled);
      const currentItem = document.activeElement.closest(".body-item");
      const currentIndex = enabledItems.findIndex(
        (item) => item.element === currentItem
      );

      if (e.key === "ArrowDown") {
        e.preventDefault();
        const nextIndex = (currentIndex + 1) % enabledItems.length;
        enabledItems[nextIndex].element.focus();
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        const prevIndex =
          (currentIndex - 1 + enabledItems.length) % enabledItems.length;
        enabledItems[prevIndex].element.focus();
      } else if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        if (currentItem) {
          currentItem.click();
        }
      } else if (e.key === "Escape") {
        this.toggleDropdown();
      } else if (e.key.length === 1 && e.key.match(/[a-z0-9]/i)) {
        console.log(e);
        e.preventDefault();
        this.handleCharacterInput(e.key);
      }
    });

    this.dropdownInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        this.toggleDropdown();
      }
    });

    document.addEventListener("click", () => {
      const isOpen =
        this.dropdownBody.classList.contains("open") ||
        this.dropdownBody.classList.contains("open-top");

      if (isOpen) {
        this.toggleDropdown();
      }
    });

    this.selectElement.addEventListener("change", () => {
      this.initializeDropdown();
    });
  }

  handleCharacterInput(char) {
    const inputChar = char.toLowerCase();

    const enabledItems = this.options.filter((item) => !item.option.disabled);
    const matchingItems = enabledItems.filter((item) => {
      const itemText = item.element
        .querySelector(".text")
        .textContent.toLowerCase();
      return itemText.startsWith(inputChar);
    });

    if (matchingItems.length > 0) {
      const firstMatch = matchingItems[0].element;
      firstMatch.focus();
      firstMatch.scrollIntoView({ behavior: "smooth", block: "nearest" });

      firstMatch.classList.add("highlight");
      setTimeout(() => firstMatch.classList.remove("highlight"), 200);
    }
  }
}

function handleOffcanvasClose() {
  const allOffcanvas = document.querySelectorAll(".offcanvas-wrapper");
  const body = document.querySelector("body");
  if (!allOffcanvas) return;

  allOffcanvas.forEach((offcanvas) => {
    const innerWrapper = offcanvas.querySelector(".offcanvas-inner");

    const hideModalView = () => {
      if (innerWrapper.classList.contains("hide-left")) {
        innerWrapper.classList.replace("slide-left", "show-left");
        setTimeout(() => {
          offcanvas.classList.remove("show");
          body.classList.remove("disable-scroll____catalog-section");
        }, 300);
      } else if (innerWrapper.classList.contains("hide-right")) {
        innerWrapper.classList.replace("slide-right", "show-right");
        setTimeout(() => {
          offcanvas.classList.remove("show");
          body.classList.remove("disable-scroll____catalog-section");
        }, 300);
      }
    };

    offcanvas.addEventListener("click", (event) => {
      if (event.target === offcanvas) {
        hideModalView();
      }

      const closeBtn = event.target.closest(".close-btn");
      if (closeBtn) {
        hideModalView();
        event.preventDefault();
      }
    });
  });
}

function openOffcanvas(button) {
  const modelId = button.getAttribute("data-model-id");
  const modal = document.querySelector(`#${modelId}`);
  const modalChildren = modal.firstElementChild;
  const animateItems = Array.from(modalChildren.children);
  const body = document.querySelector("body");

  const gsapTl = gsap.timeline();

  const startAnimation = (items) => {
    gsapTl.from(items, {
      duration: 0.5,
      y: 100,
      opacity: 0,
      stagger: 0.1,
    });
  };

  if (modalChildren.classList.contains("hide-left")) {
    modal.classList.add("show");
    body.classList.add("disable-scroll____catalog-section");
    setTimeout(() => {
      startAnimation(animateItems);
      modalChildren.classList.replace("show-left", "slide-left");
    }, 200);
  } else if (modalChildren.classList.contains("hide-right")) {
    modal.classList.add("show");
    body.classList.add("disable-scroll____catalog-section");
    setTimeout(() => {
      startAnimation(animateItems);
      modalChildren.classList.replace("show-right", "slide-right");
    }, 200);
  }
}

const toggleOrderSummary = () => {
  const wrappers = document.querySelectorAll(".cart-summary__panel");
  wrappers.forEach(wrapper => {
    const btn = wrapper.querySelector(".cart-summary__toggle");
    btn.addEventListener("click", () => {
      if (wrapper.classList.contains("open")) {
        wrapper.classList.remove("open");
      }
      else {
        wrapper.classList.add("open");
      }
    })
  });
}

function handleDonationRadio() {
  const makeDonationWrappers = document.querySelectorAll(".make-donation-wrapper");
  if (!makeDonationWrappers) return;

  makeDonationWrappers.forEach(wrapper => {
    const donationCheckbox = wrapper.querySelector("checkbox");
    if (!donationCheckbox) return;

    const radioWrapper = wrapper.querySelector(".custom-radio__wrapper");
    const defaultRadioItems = radioWrapper?.querySelectorAll(".custom-radio");
    const customInput = radioWrapper?.querySelector(".custom-input");
    const customInputBtn = radioWrapper?.querySelector(".custom-input-btn");



    if (!defaultRadioItems) return;

    defaultRadioItems.forEach(defaultRadioItem => {
      // if user check it then  also make donationCheckbox check else uncheck
      

      
    });




    





    
  });
}

function openModal(button) {
    const body = document.body;
    const scrollBarWidth =
        window.innerWidth - document.documentElement.clientWidth;
    const modalID = button.getAttribute("data-modal-id");
    const modal = document.querySelector(`#${modalID}`);

    if (!modal) {
        console.error("No modal found linked to", button);
        return;
    }

    modal.classList.add("show");
    body.style.paddingRight = `${scrollBarWidth}px`;
    body.classList.add("overflow-hidden");
}

function handleCloseModals() {
    const modals = document.querySelectorAll(".modal");
    const body = document.body;

    const closeModalWithAnimation = (modal) => {
        const innerModal = modal.querySelector(".inner-modal");
        const modalOpen = modal.classList.contains("show");
        const animationStyle = innerModal.style.animationName === "modal-closing";

        if (!modalOpen || animationStyle) return;
        innerModal.style.animation = "modal-closing 0.5s forwards";

        // const onAnimationEnd = () => {
        //   modal.classList.remove("show");
        //   innerModal.style.animation = "";
        //   innerModal.removeEventListener("animationend", onAnimationEnd);
        //   body.classList.remove("overflow-hidden");
        //   body.style.paddingRight = "";
        // };

        const onAnimationEnd = () => {
            modal.classList.remove("show");
            innerModal.style.animation = "";
            innerModal.removeEventListener("animationend", onAnimationEnd);

            const anyOtherModalOpen =
                document.querySelectorAll(".modal.show").length > 1;

            if (!anyOtherModalOpen) {
                body.classList.remove("overflow-hidden");
                body.style.paddingRight = "";
            }
        };

        innerModal.addEventListener("animationend", onAnimationEnd);
    };

    modals.forEach((modal) => {
        const closeBtns = modal.querySelectorAll(".close-btn");
        const innerModal = modal.querySelector(".inner-modal");

        closeBtns.forEach((closeBtn) => {
            closeBtn.addEventListener("click", () => closeModalWithAnimation(modal));
        });

        if (!innerModal) return;

        innerModal.addEventListener("click", (e) => {
            if (e.target === innerModal) {
                closeModalWithAnimation(modal);
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
  setHeaderHeight();
  handleCustomAccordion();
  handlePriceRangeSlider();
  handleOffcanvasClose();
  toggleOrderSummary();
  handleDonationRadio();
  handleCloseModals();
});

window.addEventListener("resize", setHeaderHeight);


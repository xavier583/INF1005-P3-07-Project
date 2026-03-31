document.addEventListener("DOMContentLoaded", function () {
	var popupLinks = document.querySelectorAll(".js-popup-link");
	var modalElement = document.getElementById("footerInfoModal");
	var modalTitleElement = document.getElementById("footerInfoModalTitle");
	var modalBodyElement = document.getElementById("footerInfoModalBody");

	if (!popupLinks.length || !modalElement || !window.bootstrap) {
		return;
	}

	var popupContent = {
		"cookies-settings": {
			title: "Cookies Settings",
			body: "<p>Manage your cookie preferences for analytics and personalized content. You can update your choices at any time, and only essential cookies are always active for site functionality.</p>"
		},
		"privacy-center": {
			title: "Privacy Center",
			body: "<p>Maison Reluxe collects only the information needed to process orders, improve service, and provide support. We do not sell your personal data, and you can request access or deletion through Customer Care.</p>"
		},
		"terms-of-use": {
			title: "Terms of Use",
			body: "<p>By using this website, you agree to our usage terms, including acceptable use, account responsibilities, and intellectual property protections for all content and product media.</p>"
		},
		"terms-and-conditions": {
			title: "Terms and Conditions",
			body: "<p>Orders are subject to stock availability and payment verification. Shipping timelines are estimates, and return eligibility follows the policy shown at checkout and within your order summary.</p>"
		},
		"sitemap": {
			title: "Sitemap",
			body: "<p>Browse all major sections: Home, Products, Category pages, Product Detail pages, Cart, Checkout, Profile, and Reviews.</p>"
		}
	};

	var footerInfoModal = new bootstrap.Modal(modalElement);

	popupLinks.forEach(function (link) {
		link.addEventListener("click", function (event) {
			event.preventDefault();

			var popupId = link.dataset.popupId;
			var selectedContent = popupContent[popupId];

			if (!selectedContent) {
				modalTitleElement.textContent = "Information";
				modalBodyElement.innerHTML = "<p>Details for this section will be available soon.</p>";
			} else {
				modalTitleElement.textContent = selectedContent.title;
				modalBodyElement.innerHTML = selectedContent.body;
			}

			footerInfoModal.show();
		});
	});
});

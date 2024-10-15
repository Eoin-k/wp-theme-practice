const body = document.body;
const searchInput = document.getElementById("search-term");
const openButton = document.querySelectorAll(".fa-search");
const searchOverlay = document.querySelector(".search-overlay");
const closeButton = document.querySelector(".search-overlay__close");
const resultsContainer = document.getElementById("search-overlay__results");
const inputTags = ["INPUT", "TEXTAREA"];

let spinnerVisible = false;
let isOpen = false;
let storedSearchValue = "";
let timer;
const openSearch = () => {
	searchOverlay.classList.add("search-overlay--active");
	body.classList.add("body-no-scroll");
	if (document.activeElement !== searchInput) {
		setTimeout(() => {
			searchInput.focus();
		}, 50);
	}
};

const closeSearch = () => {
	searchOverlay.classList.remove("search-overlay--active");
	body.classList.remove("body-no-scroll");
	searchInput.value = "";
	resultsContainer.innerHTML = "";
};

const search = (searchValue) => {
	clearTimeout(timer);
	timer = setTimeout(() => {
		searchResults(searchValue);
	}, 750);
};

const searchResults = async (searchValue) => {
	try {
		const req = await fetch(`${university.root}/wp-json/university/v1/search?term=${searchValue}`);

		const newData = await req.json();
		console.log(newData);

		resultsContainer.innerHTML = `
		<div class="row">
		<div class="one-third">
		<h2 class="search-overlay__section-title">General Information</h2>
		${
			newData.generalInfo.length
				? `<ul class="link-list min-list">
			${newData.generalInfo
				.map(
					(result) =>
						`<li><a href="${result.permalink}">${result.title}</a> ${
							result.author ? `By: ${result.author}` : ""
						}</li>`,
				)
				.join("")} </ul>`
				: "<p>No results to show</p>"
		}
</div>

		<div class="one-third">
		<h2 class="search-overlay__section-title">Programs</h2>
		${
			newData.programs.length
				? `<ul class="link-list min-list">
			${newData.programs
				.map((result) => `<li><a href="${result.permalink}">${result.title}</a></li>`)
				.join("")} </ul>`
				: `<p>No programs match that search <a href="${university.root}/programs">View All Programs</a> </p>`
		}
		<h2 class="search-overlay__section-title">Professors</h2>
		${
			newData.professors.length
				? `<ul class="professor-cards">
			${newData.professors
				.map(
					(
						result,
					) => `<li class="professor-card__list-item"><a class="professor-card" href="${result.permalink}">
						<img class="professor-card__image" src="${result.image}" alt="">
						<span class="professor-card__name">${result.title}</span>
					</a></li>`,
				)
				.join("")} </ul>`
				: "<p>No results to show</p>"
		}
		</div>
		<div class="one-third">
		<h2 class="search-overlay__section-title">Campuses</h2>
		${
			newData.campuses.length
				? `<ul class="link-list min-list">
			${newData.campuses
				.map((result) => `<li><a href="${result.permalink}">${result.title}</a></li>`)
				.join("")} </ul>`
				: `<p>No campuses match that search <a href="${university.root}/campuses">View All Campuses</a> </p>`
		}
		<h2 class="search-overlay__section-title">Events</h2>
		${
			newData.events.length
				? `
			${newData.events
				.map(
					(result) => `<div class="event-summary">
	<a class="event-summary__date event-summary__date--beige t-center" href="${result.permalink}">
		<span class="event-summary__month">
			${result.month}</span>
		<span class="event-summary__day">
			${result.day}</span>
	</a>
	<div class="event-summary__content">
		<h5 class="event-summary__title headline headline--tiny"><a href="${result.permalink}">${result.title}</a></h5>
		<p>${result.description}<a href="${result.permalink}" class="nu gray">Read more</a></p>
	</div>
</div>`,
				)
				.join("")} </ul>`
				: `<p>No events match that search <a href="${university.root}/events">View All Events</a> </p>`
		}
		</div>
		</div>
</div>`;
	} catch (error) {
		console.error(error);
	}
};

openButton.forEach((button) => {
	button.addEventListener("click", openSearch);
	isOpen = true;
});

closeButton.addEventListener("click", closeSearch);
searchInput.addEventListener("input", (e) => {
	let newSearchValue = e.target.value;

	if (
		searchInput.value !== "" &&
		newSearchValue.length >= 3 &&
		newSearchValue !== storedSearchValue
	) {
		if (!spinnerVisible) {
			resultsContainer.innerHTML = `<div class="spinner-loader"></div>`;
			spinnerVisible = true;
		}
		search(e.target.value);
	} else {
		resultsContainer.innerHTML = "";
		spinnerVisible = false;
	}

	storedSearchValue = e.target.value;
});

document.addEventListener("keydown", (e) => {
	if (
		(e.key == "s" || e.key == "S") &&
		isOpen == false &&
		!inputTags.includes(document.activeElement.tagName)
	) {
		isOpen = true;
		openSearch();
		return;
	}
	if ((e.key === "Escape" || e.key === "Esc") && isOpen == true) {
		isOpen = false;
		closeSearch();
		return;
	} else {
		return;
	}
});

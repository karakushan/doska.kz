/**
 * Search form filters toggle functionality
 * Handles mobile/desktop filter toggle behavior
 */
(function() {
	"use strict";
	
	var userInteracted = {}; // Объект для отслеживания взаимодействия пользователя с каждым контейнером
	
	// Функция для проверки мобильного устройства
	function isMobileDevice() {
		return (window.innerWidth <= 768);
	}
	
	// Основная функция toggle - доступна глобально
	window.toggleSearchFilters = function(header) {
		var wrapper = header.parentElement;
		var container = wrapper.querySelector(".search-container");
		var icon = header.querySelector("i");
		var form = wrapper.closest("form");
		var containerId = container.id || container.className;
		
		// Отмечаем, что пользователь взаимодействовал с этим контейнером
		userInteracted[containerId] = true;
		
		if (container.style.display === "none") {
			container.style.display = "block";
			wrapper.classList.remove("collapsed");
			if (form) form.classList.remove("filters-collapsed");
			if (icon) {
				icon.classList.remove("fa-chevron-up");
				icon.classList.add("fa-chevron-down");
			}
		} else {
			container.style.display = "none";
			wrapper.classList.add("collapsed");
			if (form) form.classList.add("filters-collapsed");
			if (icon) {
				icon.classList.remove("fa-chevron-down");
				icon.classList.add("fa-chevron-up");
			}
		}
	};
	
	// Функция для первоначальной инициализации состояния контейнера поиска
	function initSearchContainerState() {
		var searchContainers = document.querySelectorAll(".search-container");
		var isMobile = isMobileDevice();
		
		searchContainers.forEach(function(container) {
			var containerId = container.id || container.className;
			
			// Если пользователь уже взаимодействовал с этим контейнером, не меняем его состояние
			if (userInteracted[containerId]) {
				return;
			}
			
			var wrapper = container.parentElement;
			var header = wrapper.querySelector(".search-filters-header");
			var icon = header ? header.querySelector("i") : null;
			var form = wrapper.closest("form");
			
			if (isMobile) {
				// На мобильных устройствах сворачиваем только при первой загрузке
				container.style.display = "none";
				if (wrapper) wrapper.classList.add("collapsed");
				if (form) form.classList.add("filters-collapsed");
				if (icon) {
					icon.classList.remove("fa-chevron-down");
					icon.classList.add("fa-chevron-up");
				}
			} else {
				// На ПК разворачиваем
				container.style.display = "block";
				if (wrapper) wrapper.classList.remove("collapsed");
				if (form) form.classList.remove("filters-collapsed");
				if (icon) {
					icon.classList.remove("fa-chevron-up");
					icon.classList.add("fa-chevron-down");
				}
			}
		});
	}
	
	// Инициализация при загрузке страницы
	document.addEventListener("DOMContentLoaded", function() {
		initSearchContainerState();
	});
	
	// Обработка изменения размера окна - только для переключения между мобильным/десктопным режимом
	// Не трогаем контейнеры, с которыми пользователь уже взаимодействовал
	var resizeTimer;
	window.addEventListener("resize", function() {
		// Debounce для избежания частых вызовов при прокрутке
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			var searchContainers = document.querySelectorAll(".search-container");
			var isMobile = isMobileDevice();
			
			searchContainers.forEach(function(container) {
				var containerId = container.id || container.className;
				
				// Если пользователь открыл фильтры вручную, не трогаем их при resize
				if (userInteracted[containerId]) {
					return;
				}
				
				var wrapper = container.parentElement;
				var header = wrapper.querySelector(".search-filters-header");
				var icon = header ? header.querySelector("i") : null;
				var form = wrapper.closest("form");
				
				// Только при переключении с десктопа на мобильный или обратно
				// и только если пользователь еще не взаимодействовал
				var isCurrentlyHidden = container.style.display === "none" || wrapper.classList.contains("collapsed");
				
				if (isMobile && !isCurrentlyHidden) {
					// Переключились на мобильный, фильтры открыты - ничего не делаем
					// сохраняем текущее состояние (они уже открыты)
				} else if (!isMobile && isCurrentlyHidden) {
					// Переключились на десктоп, фильтры закрыты - открываем
					container.style.display = "block";
					if (wrapper) wrapper.classList.remove("collapsed");
					if (form) form.classList.remove("filters-collapsed");
					if (icon) {
						icon.classList.remove("fa-chevron-up");
						icon.classList.add("fa-chevron-down");
					}
				}
			});
		}, 250);
	});
})();


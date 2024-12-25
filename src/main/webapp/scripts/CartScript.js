function remove_item(id_bundle) {
	$.ajax({
		type: "POST",
		url: BASE_URL + "cart/remove",
		data: { id_bundle },
		success: (link) => {
			window.location.href = link
		}
	})
}

function checkout(method) {
	$.ajax({
		type: "POST",
		url: BASE_URL + "cart/checkout",
		data: { method },
		success: (link) => {
			window.location.href = link
		}
	})
}
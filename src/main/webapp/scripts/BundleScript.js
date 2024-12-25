function add_cart(id_bundle) {
	$.ajax({
		type: "POST",
		url: BASE_URL + "bundle/add_cart",
		data: { id_bundle },
		success: (link) => {
			window.location.href = link
		}
	})
}
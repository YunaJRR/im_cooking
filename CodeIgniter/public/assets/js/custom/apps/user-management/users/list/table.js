"use strict";

var selectedRows = []; // Global variable to store selected rows

var KTUsersList = function() {
    var e, t, n, r, o = document.getElementById("kt_table_users"),
        c = () => {
            o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach((t => {
                t.addEventListener("click", (function(t) {
                    t.preventDefault();
                    const n = t.target.closest("tr"),
                        r = n.querySelectorAll("td")[1].querySelectorAll("a")[1].innerText;
                    Swal.fire({
                        text: "Are you sure you want to delete " + r + "?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then((function(t) {
                        t.value ? Swal.fire({
                            text: "You have deleted " + r + "!. ",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        }).then((function() {
                            e.row($(n)).remove().draw()
                        })).then((function() {
                            a()
                        })) : "cancel" === t.dismiss && Swal.fire({
                            text: customerName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        })
                    }))
                }))
            }))
        },
        l = () => {
            const c = o.querySelectorAll('[type="checkbox"]');
            t = document.querySelector('[data-kt-user-table-toolbar="base"]'),
            n = document.querySelector('[data-kt-user-table-toolbar="selected"]'),
            r = document.querySelector('[data-kt-user-table-select="selected_count"]');
            const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
            c.forEach((e => {
                e.addEventListener("click", (function() {
                    setTimeout((function() {
                        a()
                    }), 50)
                }))
            })),
            s.addEventListener("click", (function() {
                Swal.fire({
                    text: "Are you sure you want to delete selected customers?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function(t) {
                    t.value ? Swal.fire({
                        text: "You have deleted all selected customers!.",
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    }).then((function() {
                        c.forEach((t => {
                            if (t.checked) {
                                selectedRows.push(t.closest("tr")); // Store selected row
                                e.row($(t.closest("tbody tr"))).remove().draw();
                            }
                        }));
                        o.querySelectorAll('[type="checkbox"]')[0].checked = !1
                    })).then((function() {
                        a(), l()
                    })) : "cancel" === t.dismiss && Swal.fire({
                        text: "Selected customers was not deleted.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    })
                }))
            }));
        };
        const updateSelectedRowsDisplay = () => {
            const selectedData = e.rows('.selected').data();
            selectedRows = Array.from(selectedData); // Update the global selectedRows array
            console.log(selectedRows); // Display selected rows in the console or update UI
        };
    const a = () => {
        const e = o.querySelectorAll('tbody [type="checkbox"]');
        let c = !1,
            l = 0;
        e.forEach((e => {
            e.checked && (c = !0, l++)
        })),
        c ? (r.innerHTML = l, t.classList.add("d-none"), n.classList.remove("d-none")) : (t.classList.remove("d-none"), n.classList.add("d-none"))
    };

    return {
        init: function() {
            o && (o.querySelectorAll("tbody tr").forEach((e => {
                const t = e.querySelectorAll("td"),
                    n = t[3].innerText.toLowerCase();
                let r = 0,
                    o = "minutes";
                n.includes("yesterday") ? (r = 1, o = "days") : n.includes("mins") ? (r = parseInt(n.replace(/\D/g, "")), o = "minutes") : n.includes("hours") ? (r = parseInt(n.replace(/\D/g, "")), o = "hours") : n.includes("days") ? (r = parseInt(n.replace(/\D/g, "")), o = "days") : n.includes("weeks") && (r = parseInt(n.replace(/\D/g, "")), o = "weeks");
                const c = moment().subtract(r, o).format();
                t[3].setAttribute("data-order", c);
                const l = moment(t[5].innerHTML, "DD MMM YYYY, LT").format();
                t[5].setAttribute("data-order", l)
            })),
            (e = $(o).DataTable({
                info: !1,
                order: [],
                pageLength: 10,
                lengthChange: !1,
                paging: false,
                columnDefs: [{
                    orderable: !1,
                    targets: 0
                }, {
                    orderable: !1,
                    targets: 6
                }]
            })).on("draw", (function() {
                l(), c(), a()
            })).on('order.dt', function () {
                const order = e.order();
                const columnIndex = order[0][0];
                const sortDirection = order[0][1];
                const columnDataName = e.settings().init().columns[columnIndex].data;

                let newUrl = window.location.pathname + "?";
                const urlParams = new URLSearchParams(window.location.search);
                const searchParams = [];
                urlParams.forEach(function(value, key) {
                    if (key !== 'sortField' && key !== 'sortOrder') {
                        searchParams.push(key + "=" + value);
                    }
                });
                newUrl += searchParams.join('&');

                if (columnDataName && sortDirection) {
                    newUrl += (searchParams.length > 0 ? '&' : '') + "sortField=" + columnDataName + "&sortOrder=" + sortDirection;
                }

                window.location.href = newUrl;

            }),
            l(),
            document.querySelector('[data-kt-user-table-filter="search"]').addEventListener("keyup", (function(t) {
                e.search(t.target.value).draw()
            })),
            document.querySelector('[data-kt-user-table-filter="reset"]').addEventListener("click", (function() {
                document.querySelector('[data-kt-user-table-filter="form"]').querySelectorAll("select").forEach((e => {
                    $(e).val("").trigger("change")
                })),
                e.search("").draw()
            })),
            c(),
            (() => {
                const t = document.querySelector('[data-kt-user-table-filter="form"]'),
                    n = t.querySelector('[data-kt-user-table-filter="filter"]'),
                    r = t.querySelectorAll("input[type='text']"); // Select input text elements

                n.addEventListener("click", (function() {
                    console.log("Apply filter clicked"); // Debugging

                    const urlParams = new URLSearchParams();

                    r.forEach(((e) => {
                        console.log("Input element name:", e.name, "value:", e.value); // Debugging
                        if (e.value && "" !== e.value) {
                            urlParams.append(e.name, e.value);
                        }
                    }));

                    console.log("URL parameters after adding input values:", urlParams.toString()); // Debugging

                    const sortField = new URLSearchParams(window.location.search).get('sortField');
                    const sortOrder = new URLSearchParams(window.location.search).get('sortOrder');

                    if (sortField && sortOrder) {
                        urlParams.append('sortField', sortField);
                        urlParams.append('sortOrder', sortOrder);
                    }

                    console.log("Final URL parameters:", urlParams.toString()); // Debugging

                    window.location.href = window.location.pathname + "?" + urlParams.toString();
                }));
            })()
        )},
        getSelectedRows: function() {
            return selectedRows;
        }
    }
}();

KTUtil.onDOMContentLoaded((function() {
    KTUsersList.init()
}));
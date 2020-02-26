$(document).ready(function() {

    $('#log-table').dataTable( {
        "language": {
            "decimal":        "",
            "emptyTable":     "אין מידע זמין",
            "info":           "מראה _START_ עד _END_ מתוך _TOTAL_ תוצאות",
            "infoEmpty":      "מראה 0 ל 0 of 0 תוצאות",
            "infoFiltered":   "(סונן מ _MAX_ סך התוצאות)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "מראה _MENU_ תוצאות",
            "loadingRecords": "טוען...",
            "processing":     "מעבד...",
            "search":         "חיפוש: ",
            "zeroRecords":    "לא נמצאו תוצאות מתאימות",
            "paginate": {
                "first":      "ראשון",
                "last":       "אחרון",
                "next":       "הבא",
                "previous":   "הקודם"
            },
            "aria": {
                "sortAscending":  ": סידור פריטים מההתחלה לסוף",
                "sortDescending": ": סידור פריטים מהסוף להתחלה"
            }
        }
    });


});
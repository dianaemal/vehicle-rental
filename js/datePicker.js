document.addEventListener("DOMContentLoaded", () => {
    // Adjust booked ranges for date picker
    
     //to should be one day before the end date:
     bookedRanges.forEach(range => {
        const originalToDate = new Date(range.to);
        const adjustedToDate = new Date(originalToDate.getTime() - 24 * 60 * 60 * 1000); // minus one day
        range.to = adjustedToDate.toISOString().split('T')[0]; // Format as "YYYY-MM-DD"
        range.from = new Date(range.from).toISOString().split('T')[0]; // Ensure from is also formatted
    });

    let startDate = null;

    // create an instance of flatpickr end_date so that the min and max dates can be set dynamically in start_date:
    const endDatePicker = flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        mode: "single", 
    });
    
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        minDate: new Date().toISOString().split('T')[0], // Disable past dates
        disable: bookedRanges.map(range => ({
            from: range.from,
            to: range.to
        })),
        onChange: function(selectedDates){
            startDate = selectedDates[0];

          // Find the first booked range that starts AFTER the selected start date
            const nextStartDate = bookedRanges.find(range => new Date (range.from )> startDate)
            let maxDate = null;
            // set max to one day after the next start date:
            if (nextStartDate) {
                const nextDate = new Date(new Date (nextStartDate.from)); // add one day
                maxDate = nextDate.toISOString().split('T')[0]; // Format as "YYYY-MM-DD"
            }
            //min date shoiuld be one day after the start date:
            const mindDate = startDate ? new Date(startDate.getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[0] : new Date().toISOString().split('T')[0];
          


            // Update the end date picker with the new min and max dates
            endDatePicker.set('minDate', mindDate);
            endDatePicker.set('maxDate', maxDate);
        }


    });


    // Price calculation functionality
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');
    const totalPrice = document.getElementById('total_price');
    const totalPriceInput = document.getElementById('total_price_input');

    function calculateTotal() {
        const startDate = new Date(start.value);
        const endDate = new Date(end.value);
        
        if (startDate && endDate && endDate >= startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const total = days * pricePerDay;
            console.log(total)
            
            totalPrice.textContent = total.toFixed(2);
            totalPriceInput.value = total.toFixed(2);
        } else {
            totalPrice.textContent = '0.00';
            totalPriceInput.value = '0.00';
        }
    }


    // Price calculation is now handled by flatpickr onChange events
    start.addEventListener('change', calculateTotal);
    end.addEventListener('change', calculateTotal);

    // If editing, set end_date min/max on page load
    // in order for editing to work, the current date should also be excluded in sql query so that nextStartdate can be found.
    if (start.value) {
        const selectedStartDate = new Date(start.value);

        // Find the first booked range that starts AFTER the selected start date
        const nextStartDate = bookedRanges.find(range => new Date(range.from) > selectedStartDate);
        let maxDate = null;
        if (nextStartDate) {
            const nextDate = new Date(nextStartDate.from);
            maxDate = nextDate.toISOString().split('T')[0];
        }
        const mindDate = new Date(selectedStartDate.getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[0];

        endDatePicker.set('minDate', mindDate);
        endDatePicker.set('maxDate', maxDate);
    }
});
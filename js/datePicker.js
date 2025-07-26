document.addEventListener("DOMContentLoaded", () => {
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

    // Total price calculation:
    const start = document.getElementById('start_date');
        const end = document.getElementById('end_date');
        const totalPrice = document.getElementById('total_price');
        const totalPriceInput = document.getElementById("total_price_input");

        
        function calculateTotal(){
            const startDate = new Date(start.value);
            const endDate = new Date(end.value);
            if (startDate && endDate && endDate >= startDate){
                //Note: Date returns the number of milliseconds since January 1, 1970, 00:00:00 UTC
                const timeDiff = endDate - startDate;
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1; // +1 to include the start day
                const total = days * pricePerDay;
                totalPrice.textContent = total.toFixed(2);
                totalPriceInput.value = total.toFixed(2); // Update the hidden input field
            }
            else {
                totalPrice.textContent = '0.00'; // Reset if dates are invalid
            }
        }
        start.addEventListener('change', calculateTotal);
        end.addEventListener('change', calculateTotal);
});
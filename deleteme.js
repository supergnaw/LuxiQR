//-------------------------------------------------------------
//-----------------Do not edit the XML tags--------------------
//-------------------------------------------------------------

//<AcroForm>
//<ACRO_source>1351-2_10eCalculate:Annot1:MouseUp:Action1</ACRO_source>
//<ACRO_script>
/*********** belongs to: AcroForm:1351-2_10eCalculate:Annot1:MouseUp:Action1 ***********/

function autoCalculate() {
    // Calculate Mileage
    var totalMiles = [
            "1351-2_MilesPlace02",
            "1351-2_MilesPlace03",
            "1351-2_MilesPlace04",
            "1351-2_MilesPlace05",
            "1351-2_MilesPlace06",
            "1351-2_MilesPlace07",
            "1351-2_MilesPlace08",
        ].map(name => this.getField(name).value || 0)
        .reduce((a, b) => a + b, 0);

    this.getField("1351-2_10e3").value = totalMiles * parseFloat(this.getField("1351-2_MileageRate").value);

    // Calculate Reimbursable Expenses
    var totalReimbursable = [
            "1351-2_18d1",
            "1351-2_18d2",
            "1351-2_18d3",
            "1351-2_18d4",
            "1351-2_18d5",
            "1351-2_18d6",
            "1351-2_18d7",
            "1351-2_18d8",
            "1351-2_18d9"
        ].map(name => this.getField(name).value || 0)
        .reduce((a, b) => a + b, 0);

    this.getField("1351-2_10e6").value = totalReimbursable;

    // Calculate Totals
    var total = [
            "1351-2_10e1",
            "1351-2_10e2",
            "1351-2_10e3",
            "1351-2_10e4",
            "1351-2_10e5",
            "1351-2_10e6"
        ].map(name => this.getField(name).value || 0)
        .reduce((a, b) => a + b, 0);

    this.getField("1351-2_10e7").value = total;
    var previousPayments = this.getField("1351-2_9PreviousPayments").value || 0;

    // Calculate Less Advance
    var lessAdvance = total - previousPayments;
    this.getField("1351-2_10e8").value = lessAdvance;

    // Calculate Amount Owed (if LessAdvance is negative, show positive value, otherwise 0)
    this.getField("1351-2_10e9").value = lessAdvance < 0 ? Math.abs(lessAdvance) : 0;

    // Calculate Amount Due (if LessAdvance is positive, show value, otherwise 0)
    this.getField("1351-2_10e10").value = lessAdvance >= 0 ? lessAdvance : 0;
}

// Run the auto-calculation function
autoCalculate();


//</ACRO_script>
//</AcroForm>



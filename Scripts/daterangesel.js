// dateRangePicker.js

export function createDateRangePicker({
    containerId,
    startInputId,
    endInputId,
    displayId,
    submitButtonId,
    days = 14
}) {
    const container = document.getElementById(containerId);
    const startInput = document.getElementById(startInputId);
    const endInput = document.getElementById(endInputId);
    const display = document.getElementById(displayId);
    const submit = document.getElementById(submitButtonId);

    let startDate = null;
    let endDate = null;
    const buttons = [];

    // Generate date buttons
    for (let i = 0; i < days; i++) {
        const date = new Date();
        date.setDate(date.getDate() + i);

        const iso = date.toISOString().split("T")[0];

        const btn = document.createElement("button");
        btn.innerText = iso;
        btn.classList.add("date-btn");
        btn.dataset.iso = iso;
        btn.type = "button";

        btn.addEventListener("click", () => handleSelect(btn));

        container.appendChild(btn);
        buttons.push(btn);
    }

    function handleSelect(btn) {
        const selected = btn.dataset.iso;

        if (!startDate) {
            startDate = selected;
            endDate = null; 
            highlightRange();
            return;
        }

        if (!endDate) {
            if (selected < startDate) {
                endDate = startDate;
                startDate = selected;
            } else {
                endDate = selected;
            }
            highlightRange();
            updateFormValues();
            return;
        }

        // Reset selection
        startDate = selected;
        endDate = null;
        highlightRange();
    }

    function highlightRange() {
        buttons.forEach(b => b.classList.remove("selected", "in-range"));

        if (!startDate) return;

        const startBtn = buttons.find(b => b.dataset.iso === startDate);
        startBtn.classList.add("selected");

        if (endDate) {
            const endBtn = buttons.find(b => b.dataset.iso === endDate);
            endBtn.classList.add("selected");

            buttons.forEach(b => {
                if (b.dataset.iso > startDate && b.dataset.iso < endDate) {
                    b.classList.add("in-range");
                }
            });
        }
    }

    function updateFormValues() {
        startInput.value = startDate;
        endInput.value = endDate;

        display.innerText = `${startDate} → ${endDate}`;
        submit.style.display = "block";
    }
}

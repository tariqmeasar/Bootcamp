const userInput = document.getElementById("userInput");
const unsafeBtn = document.getElementById("unsafeBtn");
const safeBtn = document.getElementById("safeBtn");
const outputContainer = document.getElementById("outputContainer");

unsafeBtn.addEventListener("click", function() {
    const value = userInput.value;
    outputContainer.innerHTML = value;
});

safeBtn.addEventListener("click", function() {
    const value = userInput.value;
    outputContainer.innerHTML = "";
    const safeParagraph = document.createElement("p");
    safeParagraph.textContent = value;
    outputContainer.appendChild(safeParagraph);
});

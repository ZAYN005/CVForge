
const inputs = document.querySelectorAll("input");
const previewName = document.getElementById("previewName");
const previewContact = document.getElementById("previewContact");

inputs.forEach(i => i.addEventListener("input", updatePreview));

function updatePreview() {
  const first = document.getElementById("firstName").value || "Your";
  const last = document.getElementById("lastName").value || "Name";
  const city = document.getElementById("city").value;
  const state = document.getElementById("state").value;
  const email = document.getElementById("email").value;
  const phone = document.getElementById("phone").value;

  previewName.textContent = `${first} ${last}`;
  previewContact.textContent =
    `${city ? city + ", " : ""}${state} | ${phone} | ${email}`;
}


document.querySelector(".save").addEventListener("click", async () => {
  const form = document.getElementById("headerForm");
  const data = new FormData(form);

  const res = await fetch("../cvforge_api/save_resume.php", {
    method: "POST",
    body: data,
  });
  const text = await res.text();
  alert(text);
});

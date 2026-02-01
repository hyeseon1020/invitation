// 페이지 로드 확인
document.addEventListener("DOMContentLoaded", function () {
  console.log("common.js loaded");
});

// 참석 여부 선택 시 인원 입력 제어
function toggleGuestCount(selectEl) {
  const countInput = document.querySelector("input[name='guest_count']");
  if (!countInput) return;

  if (selectEl.value === "N") {
    countInput.value = 0;
    countInput.setAttribute("readonly", true);
  } else {
    if (countInput.value == 0) countInput.value = 1;
    countInput.removeAttribute("readonly");
  }
}

// RSVP 폼 유효성 검사
function validateRsvpForm(form) {
  // 1. 전송 직전에 'display_hp'의 값을 가져와서 다시 'hidden_hp'에 동기화
  const displayHp = document.getElementById('display_hp').value;
  const cleanHp = displayHp.replace(/[^0-9]/g, ''); // 숫자만 남기기
  document.getElementById('hidden_hp').value = cleanHp;

  // 2. 이름 검사
  if (form.guest_name.value.trim() === "") {
      alert("이름을 입력해주세요.");
      form.guest_name.focus();
      return false;
  }

  // 3. 전화번호 길이 검사
  if (cleanHp.length < 10 ) { // 최소 10자 (0101234567) 이상
      alert("올바른 전화번호를 입력해주세요.");
      document.getElementById('display_hp').focus();
      return false;
  }

  // 모든 검사를 통과하면 true를 반환하여 폼 전송 진행
  return true;
}



// 전화번호 하이픈
function formatPhoneNumber(el) {
    // 1. 숫자만 추출
    let val = el.value.replace(/[^0-9]/g, '');
    
    // 2. 서버 전송용 hidden 필드에 숫자만 저장
    document.getElementById('hidden_hp').value = val;

    // 3. 화면 표시용 포맷팅 (010-0000-0000)
    if (val.length <= 3) {
        el.value = val;
    } else if (val.length <= 7) {
        el.value = val.slice(0, 3) + '-' + val.slice(3);
    } else {
        el.value = val.slice(0, 3) + '-' + val.slice(3, 7) + '-' + val.slice(7, 11);
    }
}
from fastapi import FastAPI, Query

app = FastAPI(title="Dich vu Python DT-17", version="1.0.0")

STANDARDS = [
    {"code": "LS-01", "subject": "Lịch sử", "outcome": "Tìm hiểu di tích lịch sử quốc gia, truyền thống giữ nước."},
    {"code": "SH-02", "subject": "Sinh học", "outcome": "Hệ sinh thái rừng, thực vật đặc hữu và bảo tồn thiên nhiên."},
    {"code": "DL-03", "subject": "Địa lý", "outcome": "Nhận diện địa hình đá vôi karst, phát triển du lịch bền vững."},
    {"code": "CN-04", "subject": "Công nghệ", "outcome": "Quy trình sản xuất thủ công truyền thống tại làng nghề."},
    {"code": "TN-05", "subject": "Trải nghiệm", "outcome": "Kỹ năng làm việc nhóm, tự phục vụ và sinh tồn cơ bản."}
]

def text_similarity(text1: str, text2: str) -> float:
    # Thuật toán so khớp từ khóa cơ bản bằng Pure Python (thay thế scikit-learn)
    words1 = set(text1.lower().split())
    words2 = set(text2.lower().split())
    if not words1 or not words2: 
        return 0.1
    intersection = words1.intersection(words2)
    score = (len(intersection) / len(words1)) * 1.5
    return min(score + 0.25, 0.95) # Đảm bảo điểm số luôn hợp lý mô phỏng AI

@app.get("/healthz")
def healthz():
    return {"status": "ok", "service": "dt17-python"}

@app.get("/map-curriculum")
def map_curriculum(text: str = Query("di tích lịch sử"), k: int = 3):
    ranked = []
    for std in STANDARDS:
        content = std["subject"] + " " + std["outcome"]
        score = text_similarity(text, content)
        ranked.append((std, score))
    
    # Sắp xếp theo điểm số từ cao xuống thấp
    ranked.sort(key=lambda x: x[1], reverse=True)
    
    return {
        "query": text,
        "mappings": [{**item, "match_score": round(score, 2)} for item, score in ranked[:k]]
    }